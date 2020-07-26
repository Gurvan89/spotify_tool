<?php
require __DIR__ . '/autoload.php';

use SpotifyApp\Database\DatabaseFactory;
use SpotifyApp\SpotifyAccess\SpotifyApi;
use SpotifyApp\SpotifyAccess\SpotifyAuth;

//initialized the sessiosn
session_start();

//get the current path for the routing
$request = parse_url($_SERVER['REQUEST_URI']);

//Instantiate spotify authentication
$sa = new SpotifyAuth(
    $_ENV["CLIENT_ID"],
    $_ENV["CLIENT_SECRET"],
    $_ENV["BASE_URL"]
);

//If the session doesn't have email, it's because  it's not initialized yet
// Exception for callback and login because it's the process to initialize the session
if (!isset($_SESSION['email']) && $request["path"] !== "/login" && $request["path"] !== "/callback") {
    include __DIR__ . '/src/Views/login.php';
    return;
}

// In this condition is true the token we have in our db is not available anymore.
// We are using spotify API to refresh it. 
if (isset($_SESSION['email']) && $sa->checkSession($_SESSION['email'])) {
    $sa->refrechToken($_SESSION['email']);
}

switch ($request["path"]) {
    case "/login":
        //send athentication to spotify
        $sa->sendAuthentication();
        break;
    case "/callback":
        //get argument from the urr
        $args = [];
        parse_str($request["query"], $args);

        //handle the callback to fetch user with auth token
        $user = $sa->handleCallback($args);
        $_SESSION['email'] = $user->getEmail();
        header("Location: http://localhost/playlists");
        break;
    case "/createplaylist":
        //If Method==POST create a new playlist
        //If Method==GET open playlist create view 
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            if (!$_POST["name"] || trim($_POST["name"]) === null) {
                $_SESSION['error'] = "Name of your playlist is mandatory";
                include __DIR__ . '/src/Views/createPlaylist.php';
            }
            unset($_SESSION['error']);
            $sApi = getSpotifyApi($_SESSION['email']);
            $sApi->createPlaylist($_POST);
            header("Location: http://localhost/playlists");
        } else {
            include __DIR__ . '/src/Views/createPlaylist.php';
        }
        break;
    case "/updateplaylist":
        //If Method==POST update a new playlist
        //If Method==GET open playlist create view with field filled
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            if (!$_POST["name"] || trim($_POST["name"]) === null) {
                $_SESSION['error'] = "Name of your playlist is mandatory";
                include __DIR__ . '/src/Views/createPlaylist.php';
            }
            unset($_SESSION['error']);
            $sApi = getSpotifyApi($_SESSION['email']);
            $sApi->updatePlaylist($_POST);
            header("Location: http://localhost/playlists");
        } else {
            include __DIR__ . '/src/Views/createPlaylist.php';
        }
        break;
    case "/tracks":
        //If Method==POST send a request to spotify to do the research
        //If Method==GET open tracks view 
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            if (!$_POST["keyWord"] || trim($_POST["keyWord"]) === null) {
                $_SESSION['error'] = "keyWord for your research is mandatory";
                include __DIR__ . '/src/Views/tracks.php';
            }
            unset($_SESSION['error']);
            $sApi = getSpotifyApi($_SESSION['email']);
            $tracks = $sApi->searchTrack($_POST["keyWord"]);
            $_SESSION["tracks"] = $tracks;
            header("Location: http://localhost/tracks");
        } else {
            include __DIR__ . '/src/Views/tracks.php';
        }
        break;
    case "/player":
        include __DIR__ . '/src/Views/player.php';
        break;
    case "/error":
        include __DIR__ . '/src/Views/error.php';
        break;
    case "/disconnection":
        session_destroy();
        include __DIR__ . '/src/Views/login.php';
        break;
    case "/playlists":
    default:
        $sApi = getSpotifyApi($_SESSION['email']);
        //get all existing playlists from spotify api service
        $playslits = $sApi->getPlaylists();
        //Put playlist in session in order to display it in playlists view
        $_SESSION['playlists'] = $playslits;
        include __DIR__ . '/src/Views/playlists.php';
        break;
}

/**
 * Private function to fetch the current user in database and instanciate 
 * the class of spotify service api
 *
 * @param string $email
 * @return SpotifyApi
 */
function getSpotifyApi(string $email): SpotifyApi
{
    //Class to provide user from db
    $userDb = DatabaseFactory::getUserDb();
    //get user with spotify token by email from own db
    $user = $userDb->getByEmailWithToken($email);
    //instantiate the class Spotify api in order to access of spotify api service
    if ($user === null)
        include __DIR__ . '/src/Views/login.php';
    return new SpotifyApi($user->getSpotifyToken(), $user);
}

include "src/Views/footer.php";
