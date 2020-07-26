<?php

namespace SpotifyApp\Database;

include_once 'SqlConnector.php';
include_once 'UserDatabase.php';
include_once 'TokenDatabase.php';

class DatabaseFactory
{
    /**
     * SqlConnector
     */
    private static ?SqlConnector $connector = null;

    /**
     * Create a instance of Sql connector if it doesn't exist
     *
     * @return SqlConnector
     */
    private static function getInstance(): SqlConnector
    {
        //If you need to change environment var go to autoad.php
        if (self::$connector == null) {
            self::$connector = new SqlConnector(
                $_ENV["DB_ACCESS"],
                $_ENV["DB_HOST"],
                $_ENV["DB_USER"],
                $_ENV["DB_PASSWORD"],
                $_ENV["DB_NAME"],
            );
        }
        return self::$connector;
    }

    public static function getUserDb(): UserDatabase
    {
        return new UserDatabase(self::getInstance());
    }

    public static function getTokenDb(): TokenDatabase
    {
        return new TokenDatabase(self::getInstance());
    }
}
