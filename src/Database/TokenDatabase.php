<?php

namespace SpotifyApp\Database;

include_once 'AbstractDatabase.php';

use Exception;
use PDOException;
use SpotifyApp\Entity\SpotifyResponseToken;
use SpotifyApp\Entity\User;

/**
 * Table name token
 * To connect user to database
 */
class TokenDatabase extends AbstractDatabase
{
    function __construct(SqlConnector $sqlConnector)
    {
        parent::__construct($sqlConnector);
    }

    /**
     * To delete one user
     *
     * @param integer $id
     * @return void
     */
    function delete(int $id): void
    {
        $query = "DELETE FROM token WHERE id = " . $id;
        $this->executeQuery($query);
    }

    /**
     * Get user from database by id
     *
     * @param integer $id
     * @return User|null
     */
    function getById(int $id): ?User
    {
        $query = "SELECT * FROM token WHERE id = " . $id;
        $tokenFromDb = $this->executeQuery($query);
        return $tokenFromDb === null ? null : new SpotifyResponseToken($tokenFromDb);
    }


    /**
     * To insert token in db
     *
     * @param SpotifyResponseToken $token
     * @return integer
     */
    function insert(SpotifyResponseToken $token): int
    {
        $query = "INSERT INTO token (token,expire,refresh_token) VALUES (?,?,?)";
        try {
            $this->db->prepare($query)->execute([$token->getToken(), $token->getExpire()->format('Y-m-d H:i:s'),$token->getRefreshToken()]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception('Erreur : ' . $e->getMessage());
        }
    }

    /**
     * Get token object by token
     *
     * @param string $token
     * @return SpotifyResponseToken|null
     */
    function getByToken(string $token): ?SpotifyResponseToken
    {
        $query = "SELECT * FROM token WHERE token = " . $token;
        $tokenFromDb = $this->executeQuery($query);
        return $tokenFromDb === null ? null : new SpotifyResponseToken($tokenFromDb);
    }

    /**
     * Get token object by string
     *
     * @param SpotifyResponseToken $token
     * @return void
     */
    function update(SpotifyResponseToken $token)
    {
        $query = "UPDATE token SET token=?,expire=?,refresh_token=? WHERE id=?";
        
        try {
            $this->db->prepare($query)->execute([$token->getToken(), $token->getExpire()->format('Y-m-d H:i:s'), $token->getRefreshToken(), $token->getId()]);
        } catch (PDOException $e) {
            throw new Exception('Erreur : ' . $e->getMessage());
        }
    }
}
