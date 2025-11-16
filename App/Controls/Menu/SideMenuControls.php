<?php

namespace App\Controls\Menu;

use App\Core\Config;
use App\Core\AuthSession;

class SideMenuControls
{
    public static function getSideMenu(): string
    {
        /**
         * Static Menu
         */
        $dataContent = '';
        $path = Config::$DIR_BASE . '/App/Static/Menu/SideMenu.json';

        if (file_exists($path)) {
            $dataContent = file_get_contents($path);
        }

        /**
         * Permissions
         */
        $data = self::applyPermissions($dataContent);
        $dataContent = json_encode($data);

        return $dataContent;
    }

    private static function applyPermissions(string $dataContent): object
    {
        $data = json_decode($dataContent);

        /**
         * Remove ADMIN menu if user is not ADMINISTRADOR PORTAL
         * This is a security measure to prevent unauthorized access to the portal menu.
         * ADMINISTRADOR PORTAL profile is exclusive to the Portal.
         */
        $isRemove = true;
        if (isset(AuthSession::get()['PROFILE'])) {
            if (in_array(strtoupper(\App\Core\AuthSession::get()['PROFILE']), array_merge(Config::getTypeUsersAdminPortal(), Config::getTypeUsersSupportPortal()))) {
                $isRemove = false;
            }
        }
        if ($isRemove) {
            unset($data->SideMenu[1]); // Menu PORTAL SITI //
        }

        /**
         * Remove CONTROLE DE ACESSO menu if user is not ADMIN ACCOUNT
         * This is a security measure to prevent unauthorized access to the portal access control.
         * The ADMIN ACCOUNT profile is common to the user who owns the repository itself.
         */
        $isRemove = true;
        if (isset(AuthSession::get()['PROFILE'])) {
            // if (in_array(strtoupper(\App\Core\AuthSession::get()['PROFILE']), array_merge(\App\Core\Config::getTypeUsersSupportPortal(), \App\Core\Config::getTypeUsersAdminLocal()))) {
            if (in_array(strtoupper(\App\Core\AuthSession::get()['PROFILE']), Config::getTypeUsersSupportPortal())) {
                $isRemove = false;
            }
        }
        if ($isRemove) {
            unset($data->SideMenu[2]); // Menu CONTROLE DE ACESSO //
        }

        return $data;
    }
}
