<?php

namespace App\Models\CAS;

use App\Core\Database;
use App\Metadata\CAS\CasTknMD;
use App\Models\Traits\CrudOperationsTrait;
use App\Traits\ReferencedTables;
use DateTime;
use PDO;

class CasTknModel extends CasTknMD
{
    use CrudOperationsTrait;
    use ReferencedTables;

    /**
     * Attributes
     */
    private $att;

    /**
     * Controls
     */
    private $cnx;
    private $tbl = 'CasTkn';
    private $selectedFields = array();
    private $queryOrder = '';
    private $dataRows = array();
    public $isDuplicated = false;
    public $referencedTables = array();

    public function __construct()
    {
        $this->cnx = new Database();

        $this->att = [];
        foreach (static::FIELDS_MD as $key => $value) {
            $this->att[$key] = $value['Default'] ?? null;
        }

        $this->setSelectedFields();
    }

    /**
     * GET
     */
    public function __get(string $name): mixed
    {
        if (in_array($name, static::FIELDS)) {
            return $this->att[$name] ?? null;
        }
        return null;
    }

    public function getRecords(): array
    {
        return $this->dataRows;
    }

    /**
     * SET
     */
    public function __set(string $name, mixed $value): void
    {
        if (in_array($name, static::FIELDS)) {
            $this->att[$name] = $value;
        }
    }

    public function setSelectedIndex(int | null $index = null): void
    {
        $index = $index ?? 0;

        if (isset(static::TABLE_IDX[$index])) {
            $orderFields = static::TABLE_IDX[$index][0] ?? [];
            if (!empty($orderFields)) {
                $this->queryOrder = implode(', ', $orderFields);
            } else {
                $this->queryOrder = '';
            }
        } else {
            $this->queryOrder = '';
            /**
             * LogErros
             */
            $msg_err = "Índice de tabela inválido fornecido: {$index}";
            trigger_error($msg_err, E_USER_NOTICE);

            $logData = array('program' => __FILE__, 'line' => __LINE__, 'message' => $msg_err);
            Database::setLog(json_encode($logData), 'trigger_error');
            // end LogErros
        }
    }

    /**
     * Others
     */
    private function defaultValuesForNotNulls($dtnow_str)
    {
        $this->att['CasTknBlq'] = $this->att['CasTknBlq'] ?? 'N';

        if ($this->att['CasTknBlq'] === 'N') {
            $this->att['CasTknBlqDtt'] = null;
        } elseif ($this->att['CasTknBlq'] === 'S' && empty($this->att['CasTknBlqDtt'])) {
            $this->att['CasTknBlqDtt'] = $dtnow_str;
        }

        $this->att['CasTknKey'] = (empty($this->att['CasTknKey']) ? uniqid() : $this->att['CasTknKey']); // $this->att['CasTknKey'] ?? uniqid('key_', true);
        $this->att['CasTknKeyExp'] = (empty($this->att['CasTknKeyExp']) ? null : $this->att['CasTknKeyExp']); //$this->att['CasTknKeyExp'] ?? null;
    }

    public function validateToken($searchKey = true)
    {
        $tokenValidated = false;
        $pkField = static::FIELDS_PK[0];

        $expectedBlq = 'N';

        $qry = "SELECT CasTknCod, CasTknBlq, CasTknKey, CasTknKeyExp FROM {$this->tbl} WHERE CasTknBlq = :CasTknBlq";

        $parameters = [':CasTknBlq' => $expectedBlq];

        if ($searchKey) {
            if (empty($this->att['CasTknKey'])) return false;
            $qry .= " AND CasTknKey = :CasTknKey";
            $parameters[':CasTknKey'] = $this->att['CasTknKey'];
            $orderBy = 'CasTknKey';
        } else {
            if (empty($this->att[$pkField])) return false;
            $qry .= " AND {$pkField} = :{$pkField}";
            $parameters[":{$pkField}"] = $this->att[$pkField];
            $orderBy = $pkField;
        }

        $qry .= " ORDER BY {$orderBy} LIMIT 1;";

        $stmt = $this->cnx->executeQuery(__FILE__, $qry, $parameters);

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->dataRows[0] = $result;

            $this->att[$pkField] = $result[$pkField];
            $this->att['CasTknBlq'] = $result['CasTknBlq'];
            $this->att['CasTknKey'] = $result['CasTknKey'];
            $this->att['CasTknKeyExp'] = $result['CasTknKeyExp'];

            if (!empty($result['CasTknKeyExp'])) {
                try {
                    $dtnow = new DateTime('now');
                    $dtExp = new DateTime($result['CasTknKeyExp']);
                    if ($dtnow <= $dtExp) {
                        $tokenValidated = true;
                    }
                } catch (\Exception $e) {
                    error_log("Erro ao processar data de expiração do token: " . $e->getMessage());
                    $tokenValidated = false;
                }
            } else {
                $tokenValidated = true;
            }
        } else {
            $this->dataRows = [];
        }

        return $tokenValidated;
    }
}
