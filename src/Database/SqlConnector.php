<?php

namespace SpotifyApp\Database;

use Exception;
use PDO;
use PDOException;

/**
 * Connector of sql db 
 */
class SqlConnector
{
    /**
     * Database's string access 
     */
    private string $dbAccess;

    /**
     * Database's host
     */
    private string $dbHost;

    /**
     * Database's user 
     */
    private string $dbUser;

    /**
     * Database's password 
     */
    private string $dbPassword;

    /**
     * Database's name 
     */
    private string $dbName;

    /**
     * Database
     */
    private ?PDO $db = null ;

    /**
     * Initialize sql database
     */
    public function __construct(string $dbAccess, string $host, string $user, string $dbPassword, string $dbName)
    {
        $this->dbAccess = $dbAccess;
        $this->dbHost = $host;
        $this->dbUser = $user;
        $this->dbPassword = $dbPassword;
        $this->dbName = $dbName;

        $dsn = sprintf($this->dbAccess, $this->dbHost, $this->dbName);
        if ($this->db == null) {
            try {
                $this->db = new PDO($dsn, $this->dbUser, $this->dbPassword);
            } catch (PDOException $e) {
                throw new Exception('Erreur : ' . $e->getMessage());
            }
        }
    }

    /**
     * To kill the db with the database
     */
    public function close()
    {
        $this->db = null;
    }

    /**
     * Get database
     */
    public function getDb()
    {
        return $this->db;
    }
}
