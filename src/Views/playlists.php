<?php
include "menu.php";
?>
<table class="table">
  <thead>
    <tr>
      <th scope="col">Name</th>
      <th scope="col">Description</th>
      <th scope="col">Modify</th>
      <th scope="col">Play on spotify</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $playlists = $_SESSION['playlists'];
    foreach ($playlists["items"] as $playlist) {
      $name=isset($playlist['name'])?$playlist['name']:"";
      $description=isset($playlist['description'])?$playlist['description']:"";
      $modifyUrl=sprintf("/updateplaylist?id=%s&name=%s&description=%s",$playlist["id"],urlencode(trim($name)),urlencode(trim($description)));
    ?>
      <tr>
        <td><?php echo $name ?> </td>
        <td><?php echo $description ?> </td>
        <td>
          <a href=<?php echo $modifyUrl ?>>
            <img src="/src/Views/icons/pencil.svg" alt="icon name" width="20" height="20">
          </a>
        </td>
        <td>
          <a href=<?php echo $playlist["external_urls"]["spotify"] ?> target="_blank">
            <img src="/src/Views/icons/play.svg" alt="icon name" width="20" height="20">
          </a>
        </td>
      </tr>
    <?php } ?>
  </tbody>
</table>