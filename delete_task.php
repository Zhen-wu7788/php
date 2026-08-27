<?php
    include ("config.php");;

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "DELETE FROM tasks WHERE t_id = ?";
        $stmt = $link->prepare($sql);
        $stmt->execute([$id]);
    }

    header("Location: index.php");
    exit;
?>