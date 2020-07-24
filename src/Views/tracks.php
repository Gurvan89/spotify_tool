<?php
include "menu.php";

$tracks = false;

if (isset($_SESSION["tracks"])) {
    if (isset($_SESSION["tracks"]["tracks"])) {
        $tracks = $_SESSION["tracks"]["tracks"];
    }
}
?>

<?php
if ($tracks) {
?>
    <table class="table table-striped table-dark">
        <thead>
            <tr>
                <th scope="col">Track Name</th>
                <th scope="col">Artist</th>
                <th scope="col">Album</th>
                <th scope="col">Duration (min)</th>
                <th scope="col">Play</th>
                <th scope="col">Play on spotify</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($tracks["items"] as $track) {
                $duration = isset($track['duration_ms']) ? $track['duration_ms'] / 1000 / 60 : "";
                if (is_float($duration)) {
                    $explode = explode(".", $duration, 2);
                    if (isset($explode[0]) && isset($explode[1])) {
                        $min = $explode[0] . " min ";
                        $sec = substr(intval($explode[1]) * 60 / 100, 0, 2);
                        $duration = $min . $sec . " sec.";
                    }
                }
                $urlTrack="";
                $urlTrackEmbed="";
                if(isset($track['external_urls']['spotify'])){
                    $urlTrack=$track['external_urls']['spotify'];
                    $urlTrackEmbed=str_replace("/track","/embed/track",$urlTrack);
                }
            ?>
                <tr>
                    <td><?php echo isset($track['name']) ? $track['name'] : "" ?> </td>
                    <td><?php echo isset($track['artists'][0]["name"]) ? $track['artists'][0]["name"] : "" ?> </td>
                    <td><?php echo isset($track['album']["name"]) ? $track['album']["name"] : "" ?> </td>
                    <td><?php echo $duration ?> </td>
                    <td><iframe src=<?php echo $urlTrackEmbed ?>  width="300" height="80" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe></td>
                    <td>
                        <a href=<?php echo $urlTrack ?> target="_blank">
                            <img src="/src/Views/icons/play.svg" alt="icon name" width="80" height="80">
                        </a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php
} else{
    echo "No search yet.\n";
    echo "Feel free to type your search in the top right corner.";
}
?>