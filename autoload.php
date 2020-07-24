<?php

/**
 * This file is loaded automatically when you run the request.
 * It includes the necessary dependencies
 * You can also find here the environment variables to configure your applicatio
 */

require __DIR__ . '/vendor/autoload.php';
include 'src/SpotifyAccess/SpotifyAuth.php';
include 'src/Entity/SpotifyResponseToken.php';
include 'src/Entity/User.php';
include 'src/SpotifyAccess/SpotifyApi.php';
include 'src/Database/UserDatabase.php';
include 'src/Database/TokenDatabase.php';

//website
//If you change this base url you need to change the call back url in your spotify account dev
$_ENV["BASE_URL"]="http://localhost";

//Database access
//You can use config/create_db.sql to create your database with this config
$_ENV["DB_ACCESS"]="mysql:host=%s;dbname=%s;charset=utf8";
$_ENV["DB_HOST"]="172.17.0.1";
$_ENV["DB_USER"]="spotify_user";
$_ENV["DB_PASSWORD"]="spotify_pass";
$_ENV["DB_NAME"]="spotify";

//Spotify access
$_ENV["CLIENT_ID"]="b1921ed371e14f5bbb7b36bacefa42b0";
$_ENV["CLIENT_SECRET"]="b88273b16aae41b184a14961c6cd6864";
