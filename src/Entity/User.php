<?php

namespace SpotifyApp\Entity;

/**
 * User in order to manage user session
 */
class User
{
    private int $id;
    private string $spotifyId;
    private string $name;
    private string $email;
    private ?SpotifyResponseToken $spotifyToken;

    function __construct(array $user, ?SpotifyResponseToken $spotifyToken = null)
    {
        $this->spotifyToken = $spotifyToken;
        if (isset($user["display_name"]))
            $this->name = $user["display_name"];
        if (isset($user["id"]))
            $this->id = $user["id"];
        if (isset($user["spotify_id"]))
            $this->spotifyId = $user["spotify_id"];
        if (isset($user["name"]))
            $this->name = $user["name"];
        if (isset($user["email"]))
            $this->email = $user["email"];
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of spotifyToken
     */
    public function getSpotifyToken()
    {
        return $this->spotifyToken;
    }

    /**
     * Set the value of spotifyToken
     *
     * @return  self
     */
    public function setSpotifyToken($spotifyToken)
    {
        $this->spotifyToken = $spotifyToken;

        return $this;
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of spotifyId
     */
    public function getSpotifyId()
    {
        return $this->spotifyId;
    }

    /**
     * Set the value of spotifyId
     *
     * @return  self
     */
    public function setSpotifyId($spotifyId)
    {
        $this->spotifyId = $spotifyId;

        return $this;
    }
}
