<?php
include "menu.php";
$action = "/createplaylist";
$id = "";
if (isset($_GET['id'])) {
    $action = "/updateplaylist";
    $id = $_GET['id'];
}
$name = isset($_GET['name']) ? urldecode($_GET['name']) : "";
$description = isset($_GET['description']) ? urldecode($_GET['description']) : "";
?>

<div class="container">
    <form method="post" action=<?php echo $action ?>>
        <?php if ($id !== "") { ?>
            <input hidden name="id" value=<?php echo $id ?>>
        <?php } ?>
        <div class="form-group">
            <label for="name">Name of your playlist</label>
            <input class="form-control" name="name" placeholder="name" value="<?php echo $name ?>" required>
        </div>
        <div class="form-group">
            <label for="description">Description of your playlist</label>
            <input class="form-control" name="description" placeholder="description" value="<?php echo $description ?>">
        </div>
        <button type="submit" class="btn btn-dark">Save</button>
    </form>
</div>