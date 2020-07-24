<?php

namespace SpotifyApp\SpotifyAccess;

use Exception;
use SpotifyApp\Entity\User;
use GuzzleHttp\Client;
use SpotifyApp\Entity\SpotifyResponseToken;

/**
 * This is a class in order to request spotify api
 */
class SpotifyApi
{

    /**
     * Base URI api spotify
     */
    const SPOTIFY_BASE_API_URI = "https://api.spotify.com/v1/";

    /**
     * Method : GET
     * In order to get all playlist of the curent user
     */
    const URI_GET_PLAYLISTS = "me/playlists";

    /**
     * Method : POST
     * In order to create a new playlist
     * need te replace %s by spotify_id
     */
    const URI_CREATE_PLAYLIST = "users/%s/playlists";

    /**
     * Method : PUT
     * In order to update a playlist
     * need te replace %s by playlist_id
     */
    const URI_UPDATE_PLAYLIST = "playlists/%s";


    /**
     * Method : GET
     * In order to get user information
     */
    const URI_GET_USER_INFORMATIONS = "me";

    /**
     * METHOD : GET
     * In order to search a track
     */
    const URI_SEARCH = 'search?';

    /**
     * Headers to request spotify api
     */
    private array $headers;

    /**
     * Client Guzzle to access API
     */
    private Client $clientGuzzle;

    /**
     * User
     */
    private User $user;


    public function __construct(SpotifyResponseToken $token, ?User $user = null)
    {
        if ($user !== null)
            $this->user = $user;
        $this->headers = [
            "Authorization" => sprintf('Bearer %s', $token->getToken()),
            'Content-Type' => "application/json"
        ];
        $this->clientGuzzle = new Client(
            [
                "base_uri" => self::SPOTIFY_BASE_API_URI
            ]
        );
    }

    /**
     * Api to get all playlists of user
     *
     * @return array
     */
    public function getPlaylists(): array
    {
        return $this->methodGet(self::URI_GET_PLAYLISTS);
    }


    /**
     * Create a playlist
     *
     * @param array $params
     * @return void
     */
    public function createPlaylist(array $params)
    {
        if ($this->user === null)
            throw new Exception("User is missing");
        $uri = sprintf(self::URI_CREATE_PLAYLIST, $this->user->getSpotifyId());
        $this->methodPost($uri, $params);
    }

    /**
     * Create a playlist
     *
     * @param array $params
     * @return void
     */
    public function updatePlaylist(array $params)
    {
        if ($this->user === null)
            throw new Exception("User is missing");
        $uri = sprintf(self::URI_UPDATE_PLAYLIST, $params['id']);
        unset($params['id']);
        if (is_null($params['description']) || trim($params['description']) === "")
            unset($params['description']);
        $this->methodPut($uri, $params);
    }

    /**
     * api to get information of user
     *
     * @return array
     */
    public function getUserInformation(): array
    {
        $userInformation = $this->methodGet(self::URI_GET_USER_INFORMATIONS);
        if (isset($userInformation["id"])) {
            $userInformation["spotify_id"] = $userInformation["id"];
            unset($userInformation["id"]);
        }
        return $userInformation;
    }

    /**
     * Search track
     *
     * @param string $keyWord
     * @return array Return an array of track
     */
    public function searchTrack(string $keyWord): array
    {
        $params = http_build_query(
            [
                "q" => $keyWord,
                "type" => "track",
            ]
        );
        $uri = self::URI_SEARCH . $params;
        return $this->methodGet($uri);
    }

    /**
     * Generic method with GET 
     *
     * @param string $uri
     * @return array
     */
    private function methodGet(string $uri): array
    {
        $response = $this->clientGuzzle->request(
            "GET",
            $uri,
            ["headers" => $this->headers]
        );
        return json_decode($response->getBody(), true);
    }

    /**
     * Generic method with POST 
     *
     * @param string $uri
     * @param array $body
     * @return array
     */
    private function methodPost(string $uri, array $body): array
    {
        $response = $this->clientGuzzle->request(
            "POST",
            $uri,
            [
                "headers" => $this->headers,
                "json" => $body
            ]
        );
        return json_decode($response->getBody(), true);
    }

    /**
     * Generic method with POST 
     *
     * @param string $uri
     * @param array $body
     * @return void
     */
    private function methodPut(string $uri, array $body): void
    {
        $this->clientGuzzle->request(
            "PUT",
            $uri,
            [
                "headers" => $this->headers,
                "json" => $body
            ]
        );
    }
}
