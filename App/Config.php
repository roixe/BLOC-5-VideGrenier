<?php

namespace App;

/**
 * Application configuration
 *
 * PHP version 7.0
 */
class Config
{
    /**
     * Database host
     * @var string
     */
    public static $DB_HOST = null; // Récupérer la valeur de DB_HOST depuis les variables d'environnement

    /**
     * Database name
     * @var string
     */
    public static $DB_NAME = null; // Récupérer la valeur de DB_DATABASE depuis les variables d'environnement

    /**
     * Database user
     * @var string
     */
    public static $DB_USER = null; // Récupérer la valeur de DB_USERNAME depuis les variables d'environnement

    /**
     * Database password
     * @var string
     */
    public static $DB_PASSWORD = null; // Récupérer la valeur de DB_PASSWORD depuis les variables d'environnement

    /**
     * Show or hide error messages on screen
     * @var boolean
     */
    const SHOW_ERRORS = true;
    /**
     * Initialize configuration values
     */
    public static function init()
    {
        self::$DB_HOST = getenv('DB_HOST');
        self::$DB_NAME = getenv('DB_DATABASE');
        self::$DB_USER = getenv('DB_USERNAME');
        self::$DB_PASSWORD = getenv('DB_PASSWORD');
    }
}


