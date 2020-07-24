<?php

namespace SpotifyApp\SpotifyAccess;

use DateTime;
use Exception;
use GuzzleHttp\Client;
use SpotifyApp\Database\TokenDatabase;
use SpotifyApp\Entity\SpotifyResponseToken;
use SpotifyApp\Database\UserDatabase;
use SpotifyApp\Entity\User;

/**
 * This is the spotify authentication class 
 */
class SpotifyAuth
{

    /**
     * Base URI spotify
     */
    const SPOTIFY_BASE_URI = "https://accounts.spotify.com/";

    /**
     * Scopes give different write, depends of what do you need to do
     */
    const SCOPES = "user-read-email playlist-modify-public playlist-modify-private";

    /**
     * Spotify api authentication
     */
    const SPOTIFY_URL_AUTH = "authorize?";

    /**
     * Url to get authentication token
     */
    const SPOTIFY_URL_TOKEN = "api/token";

    /**
     * To handle the spotify callback
     */
    const REDIRECT_URI = '/callback';

    /**
     * Views' location
     */
    const VIEWS_PATH = "src/Views/";

    /**
     * Client spotify ID
     */
    private string $clientId;

    /**
     * Client spotify secret
     */
    private string $clientSecret;

    /**
     * Client api
     */
    private Client $clientGuzzle;

    /**
     * Website base url
     */
    private string $baseUrl;

    public function __construct()
    {
        $this->clientId = $_ENV["CLIENT_ID"];
        $this->clientSecret = $_ENV["CLIENT_SECRET"];
        $this->baseUrl=$_ENV["BASE_URL"];
        $this->clientGuzzle = new Client(
            [
                "base_uri" => self::SPOTIFY_BASE_URI
            ]
        );
    }

    /**
     * To send auhtentication request to spotify
     * This request will be redirect automatically to the 'REDIRECT_URI'
     *
     * @return void
     */
    public function sendAuthentication(): void
    {
        $params = http_build_query(
            [
                "response_type" => "code",
                "client_id" => $this->clientId,
                "redirect_uri" => $this->baseUrl.self::REDIRECT_URI,
                "scope" => self::SCOPES
            ]
        );
        header("Location: " . self::SPOTIFY_BASE_URI . self::SPOTIFY_URL_AUTH . $params);
    }

    /**
     * In case where the user already have a session but is token is expired.
     * This request will get a new token with the refresh token store in database.
     * The new token will be store in database
     *
     * @param string $email
     * @return void
     */
    public function refrechToken(string $email): void
    {
        $userDb = new UserDatabase();
        $userFromDb = $userDb->getByEmailWithToken($email);

        $body = [
            "grant_type" => "refresh_token",
            "refresh_token" => $userFromDb->getSpotifyToken()->getRefreshToken(),
        ];

        $this->handleToken($body);
    }

    /**
     * To handle the return of spotify authentication 
     * @param array $params
     * @return User
     */
    public function handleCallback(array $params): User
    {
        if (isset($params["error"]) || !isset($params["code"])) {
            header("Location:" . self::VIEWS_PATH . "error.php?error_type=" . $params["error"]);
        }

        $body = [
            "grant_type" => "authorization_code",
            "code" => $params["code"],
            "redirect_uri" => $this->baseUrl.self::REDIRECT_URI,
        ];
        return $this->handleToken($body);
    }

    /**
     * To check if user has a current session
     * If the user has a current session return TRUE
     * Else return false
     * @param String|null email
     * @return bool
     */
    public function checkSession(?string $email): bool
    {
        if ($email === null)
            return false;
        $userDb = new UserDatabase();
        $user = $userDb->getByEmailWithToken($email);
        if (is_null($user)) {
            return false;
        }
        $now = new DateTime();
        if ($user->getSpotifyToken()->getExpire() > $now) {
            return false;
        }
        return true;
    }

    /**
     * To get token
     *
     * @param array $body
     * @return User
     */
    private function handleToken(array $body): User
    {
        $encoded = base64_encode(sprintf('%s:%s', $this->clientId, $this->clientSecret));

        $headers = [
            'Authorization' => 'Basic ' . $encoded,
            'Content-Type' => "application/x-www-form-urlencoded"
        ];

        $options = [
            "form_params" => $body,
            "headers" => $headers
        ];

        try {
            $response = $this->clientGuzzle->request(
                "POST",
                self::SPOTIFY_URL_TOKEN,
                $options,
            );
            $token = new SpotifyResponseToken(json_decode($response->getBody(), true));
            return $this->handleUser($token);
        } catch (Exception $e) {
            header("Location:" . self::VIEWS_PATH . "error.php?error_type=" . $e->getMessage());
        }
    }

    /**
     * Create or update user in db to handle his session
     *
     * @param SpotifyResponseToken $token
     * @return User
     */
    private function handleUser(SpotifyResponseToken $token): User
    {
        //instantiate spotify api in order to get user information
        $sApi = new SpotifyApi($token);
        //create a new user with the information from spotify api
        $user = new User($sApi->getUserInformation(), $token);
        //instantiate db access 
        $userDb = new UserDatabase();
        $tokenDb = new TokenDatabase();
        //Get user in db to check if already exists
        $userFromDb = $userDb->getByEmailWithToken($user->getEmail());
        //If user exists in db -> update if not insert
        if ($userFromDb != null) {
            $token->setId($userFromDb->getSpotifyToken()->getId());
            $token->setRefreshToken($userFromDb->getSpotifyToken()->getRefreshToken());
            $tokenDb->update($token);
            $user->setId($userFromDb->getId());
            $userDb->update($user);
        } else {
            $tokenId = $tokenDb->insert($token);
            $user->getSpotifyToken()->setId($tokenId);
            $userDb->insert($user);
        }
        return $user;
    }
}
