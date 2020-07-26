<?php

namespace SpotifyApp\Database;

include_once 'InterfaceDatabase.php';

use Exception;
use PDO;
use PDOException;

/**
 * Connector of mysql db 
 */
abstract class AbstractDabase implements InterfaceDatabase
{

    protected PDO $db;

    /**
     * Initialize mysql database
     */
    protected function __construct(SqlConnector $sqlConnector)
    {
        $this->db = $sqlConnector->getDb();
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
    
}
