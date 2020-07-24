<?php

namespace SpotifyApp\Entity;

use DateInterval;
use DateTime;

/**
 * To handle the response token from spotify
 */
class SpotifyResponseToken
{
    private int $id;
    private string $token;
    private DateTime $expire;
    private string $type;
    private string $scope;
    private string $refreshToken;

    public function __construct(array $response)
    {
        if (isset($response["id"]))
            $this->id = $response["id"];
        if (isset($response["access_token"]))
            $this->token = $response["access_token"];
        if (isset($response["token"]))
            $this->token = $response["token"];
        if (isset($response["expire"]))
            $this->expire = new Datetime($response["expire"]);
        if (isset($response["expires_in"])){
            $date = new DateTime();
            $date->add(new DateInterval('PT'.$response["expires_in"].'S'));
            $this->expire = $date;
        }
            
        if (isset($response["token_type"]))
            $this->type = $response["token_type"];
        if (isset($response["scope"]))
            $this->scope = $response["scope"];
        if (isset($response["refresh_token"]))
            $this->refreshToken = $response["refresh_token"];
    }

    /**
     * Get the value of refreshToken
     */ 
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    /**
     * Set the value of refreshToken
     *
     * @return  self
     */ 
    public function setRefreshToken($refreshToken)
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }

    /**
     * Get the value of scope
     */ 
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * Set the value of scope
     *
     * @return  self
     */ 
    public function setScope($scope)
    {
        $this->scope = $scope;

        return $this;
    }

    /**
     * Get the value of type
     */ 
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @return  self
     */ 
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the value of expire
     */ 
    public function getExpire()
    {
        return $this->expire;
    }

    /**
     * Set the value of expire
     *
     * @return  self
     */ 
    public function setExpire($expire)
    {
        $this->expire = $expire;

        return $this;
    }

    /**
     * Get the value of token
     */ 
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set the value of token
     *
     * @return  self
     */ 
    public function setToken($token)
    {
        $this->token = $token;

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
}
