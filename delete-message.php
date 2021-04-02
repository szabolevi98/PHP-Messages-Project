<?php
    session_start();

    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: login.php");
        exit;
    }

    require_once "config.php";

    $sql = "DELETE FROM messages WHERE id = ? AND user_id = ?";
    if ($stmt = mysqli_prepare($connection, $sql)) {
        mysqli_stmt_bind_param($stmt, "ii", $param_id, $param_user_id);
        $param_id =  $_GET["message"];
        $param_user_id = $_SESSION["id"];
        if(mysqli_stmt_execute($stmt)){
            header("location: view-message.php");
        } else {
            echo "<p><strong>Valami hiba történt, kérlek próbáld újra később!</strong></p>";
        }
        mysqli_stmt_close($stmt);
    }
    
    mysqli_close($connection);
?>