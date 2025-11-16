<?php

namespace App\Controls\Menu;

use App\Core\Config;

class PanelMenuSupportControls
{
    public static function getDataMenu(): string
    {
        /**
         * Static Menu
         */
        $dataContent = '';
        $path = Config::$DIR_BASE . '/App/Static/Menu/PanelMenuSupport.json';

        if (file_exists($path)) {
            $dataContent = file_get_contents($path);
        }

        return $dataContent;
    }
}
