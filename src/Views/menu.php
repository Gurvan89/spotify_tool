<?php
include "header.php";
//get the current path for the routing
$request = parse_url($_SERVER['REQUEST_URI']);
$path = $request["path"];
$createPlaylistActiv = $diconnectionActiv = $tracks = $playlistActiv = "";

switch ($request["path"]) {
  case '/createplaylist':
    $createPlaylistActiv = "active";
    break;
  case '/disconnection':
    $diconnectionActiv = "active";
    break;
  case '/tracks':
    $tracks = "active";
    break;
  default:
    $playlistActiv = "active";
    break;
}
?>

<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
  <ul class="navbar-nav">
    <li class="nav-item <?php echo $playlistActiv ?>">
      <a class="nav-link" href="/playlist">Playlists</a>
    </li>
    <li class="nav-item <?php echo $createPlaylistActiv ?>">
      <a class="nav-link" href="/createplaylist">Create a playlist</a>
    </li>
    <li class="nav-item <?php echo $diconnectionActiv ?>">
      <a class="nav-link" href="/disconnection">Disconnection</a>
    </li>
  </ul>
  <form class="form-inline ml-auto" method="post" action="/tracks">
      <div class="md-form my-0">
        <input class="form-control mr-sm-2" name="keyWord" type="text" placeholder="What are you looking for?" required aria-label="Search">
      </div>
    </form>
</nav>

<?php
if (isset($_SESSION['error'])) {
?>
  <div class="alert alert-warning" role="alert">
    <?php
    echo $_SESSION['error']
    ?>
  </div>
<?php
}
?>