<?php

namespace App\Traits;

use DateTime;
use App\Core\Config;

trait LogToFile
{
    public static function setLog(string $dataContent, string $type = 'error', string $directory = '', string $subdirectory = ''): void
    {
        $logBaseDir = Config::$DIR_FILE_LOG;

        if (empty($directory)) {
            $directory = 'Others';
        }

        $fullLogPath = $logBaseDir . DIRECTORY_SEPARATOR . $directory;
        if (!empty($subdirectory)) {
            $fullLogPath .= DIRECTORY_SEPARATOR . $subdirectory;
        }

        if (!is_dir($fullLogPath)) {
            // A flag 'true' permite a criação recursiva de diretórios.
            mkdir($fullLogPath, 0777, true);
        }

        $dtnow = new DateTime('now');
        $dtnow_str = $dtnow->format('Y-m-d H:i:s');
        $sufix_name = $dtnow->format('Ymd');
        $path = $fullLogPath . DIRECTORY_SEPARATOR . $type . '_' . $sufix_name . '.log';

        $dataContent_log = $dtnow_str . ' - ' . $dataContent . PHP_EOL;

        file_put_contents($path, $dataContent_log, FILE_APPEND);
    }
}
