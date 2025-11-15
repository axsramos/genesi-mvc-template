<?php

namespace App\Core;

class Config
{
    public static $DIR_BASE = null;
    public static $HOMEPAGE = null;
    public static $HOMEPAGE_METHOD = null;
    public static $PAGE_NOT_FOUND = null;
    public static $PAGE_NOT_FOUND_METHOD = null;
    private static $INSTANCE = null;

    private function __construct()
    {
        self::$DIR_BASE = dirname(__DIR__, 2);
        self::$HOMEPAGE = 'Home';
        self::$HOMEPAGE_METHOD = 'index';
        self::$PAGE_NOT_FOUND = 'PageNotFound';
        self::$PAGE_NOT_FOUND_METHOD = 'pageNotFound';
    }

    public static function getInstance(): Config
    {
        if (self::$INSTANCE === null) {
            self::$INSTANCE = new Config();
        }
        return self::$INSTANCE;
    }
}