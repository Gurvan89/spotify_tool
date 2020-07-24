<?php

namespace SpotifyApp\Database;

use Exception;
use PDO;
use PDOException;

/**
 * Connector of mysql db 
 */
abstract class MySqlDb
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

    protected $db;

    /**
     * Initialize mysql database
     */
    public function __construct()
    {
        //If you need to change environment var go to autoad.php
        $this->dbAccess = $_ENV["DB_ACCESS"];
        $this->dbHost = $_ENV["DB_HOST"];
        $this->dbUser = $_ENV["DB_USER"];
        $this->dbPassword = $_ENV["DB_PASSWORD"];
        $this->dbName = $_ENV["DB_NAME"];

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
     * To excute query
     */
    protected function executeQuery(string $query): ?array
    {
        $response = $this->db->query($query);
        $data = null;
        if ($response) {
            try {
                $data = $response->fetch();
            } catch (PDOException $e) {
                throw new Exception('Erreur : ' . $e->getMessage());
            }
            //if no data return false
            $data = !$data ? null : $data;
        }
        return $data;
    }

    abstract protected function delete(int $id);
    abstract protected function getById(int $id);
}
