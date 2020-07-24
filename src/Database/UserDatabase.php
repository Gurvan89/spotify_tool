<?php

namespace SpotifyApp\Database;

include_once 'MySqlDb.php';

use Exception;
use PDOException;
use SpotifyApp\Entity\SpotifyResponseToken;
use SpotifyApp\Entity\User;

/**
 * To connect user to database
 */
class UserDatabase extends MySqlDb
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Get user from database by id
     *
     * @param integer $id
     * @return void
     */
    function delete(int $id): void
    {
        $query = "DELETE FROM user WHERE id = " . $id;
        $response = $this->db->query($query);
        $response->fetch();
    }

    /**
     * Get user from database by id
     *
     * @param integer $id
     * @return User|null
     */
    function getById(int $id): ?User
    {
        $query = "SELECT * FROM user WHERE id = " . $id;
        $userFromDb = $this->executeQuery($query);
        return $userFromDb === null ? null : new User($userFromDb);
    }

    /**
     * To get user and token by email even if token doesn't exist
     *
     * @param string $email
     * @return User|null
     */
    function getByEmailWithToken(string $email): ?User
    {
        $query = "SELECT * FROM user as u INNER JOIN token as t ON u.token_id = t.id WHERE email = '" . $email."'";
        $userFromDb = $this->executeQuery($query);
        return $userFromDb === null ? null : new User($userFromDb, new SpotifyResponseToken($userFromDb));
    }

    /**
     * To insert user on db
     *
     * @param User $user
     * @return void
     */
    function insert(User $user)
    {
        $query = "INSERT INTO user (name,email,token_id,spotify_id) VALUES (?,?,?,?)";
        try{
            $params=[$user->getName(),$user->getEmail(),$user->getSpotifyToken()->getId(),$user->getSpotifyId()];
            $this->db->prepare($query)->execute($params);
        }catch(PDOException $e){
            throw new Exception('Erreur : ' . $e->getMessage());
        }
    }

    /**
     * To update user on db
     *
     * @param User $user
     * @return integer
     */
    function update(User $user):int
    {
        $query = "UPDATE user SET name=?,email=?,token_id=?,spotify_id=? WHERE id = ?";
        $params=[$user->getName(),$user->getEmail(),$user->getSpotifyToken()->getId(),$user->getSpotifyId(),$user->getId()];
        try{
            $this->db->prepare($query)->execute($params);
            return $this->db->lastInsertId();
        }catch(PDOException $e){
            throw new Exception('Erreur : ' . $e->getMessage());
        }
    }
}
