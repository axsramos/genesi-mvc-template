<?php

namespace App\Class\Support;

use App\Core\AuthSession;
use App\Class\Manager\ApplyApplicationSettings;

class Install
{
    public function run(string $app_id, string $runScript): void
    {
        if (AuthSession::get()['USR_LOGGED'] !== 'anonymous') {
            switch ($runScript) {
                case 'ApplyApplicationSettings':
                    $this->applyApplicationSettings($app_id);
                    break;

                default:
                    break;
            }
        }
    }

    private function applyApplicationSettings($app_id)
    {
        $obApplyApplicationSettings = new ApplyApplicationSettings();
        $obApplyApplicationSettings->run(AuthSession::get()['RPS_ID'], $app_id);
    }
}
