# Small spotify tools
## @Author
Gurvan g.giboire@gmail.com
## Intro 
This is a small spotify tools. With it, you can:
- Login to your spotify account 

- See your playlists

- Modify the playlist you want

- Create a new playlist

- Search for a track and and listen to it (Play, pause,run forwards or backwards in time)

- You can leave you session at the end.

## How does it work

This app works with an apache2 server. You can find the simple config in the folder **/config/000-default.conf**. This config is override with **.htaccess** in this project. Don't forget to enable the apache rewrite mode (**sudo a2enmod rewrite** --> restart service apache2).

You also need to have a mysql database and run the script **/config/create_db.sql**. Feel free to change the user and password if you want. 

After that you have to setting up the application with the **./autoload.php**
- Setting up the database access

- Setting up the client id and secret of spotify

- Setting up your host (according to this host you have to modify the callback on your spotify dev account)

You also need to install composer and run composer install to install guzzle dependancy. I use guzzle for my API request.

After that you can type in your host in your browser and enjoy.

## Project tree
```bash
├── config #The config apache and script sql
├── src # Code source
│   ├── Database # Database access 
│   ├── Entity # Entity image of database table
│   ├── SpotifyAccess # Authentication and access to spotify api
│   └── Views #All views of the application
│       └── icons
```
