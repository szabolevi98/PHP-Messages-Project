<?php
    session_start();

    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: login.php");
        exit;
    }

    require_once "config.php";

    $sql = "SELECT id, user_id, subject, message, DATE_FORMAT(message_date,'%Y.%m.%d. %H:%i') AS message_date FROM messages WHERE user_id = ".$_SESSION["id"];"";
    $result = mysqli_query($connection, $sql);
    
    mysqli_close($connection);
?>
 
<!DOCTYPE html>
<html lang="en">
<?php $title = "Üzenetek megtekintése"; include "head.php"; ?>
<body class="text-center">
    <div class="container py-5">
        <div class="form bg-light shadow border rounded p-4">
            <form class="form-signin">
                <h2 class="font-weight-normal">Üzenetek</h2>
                <p class="text-muted">Itt találod meg az üzeneteidet.</p>
                <div class="row justify-content-center">
                <?php
                    if (mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_assoc($result)) {
                            echo "<div class='col-auto md-1 card p-3 m-3'><strong>Tárgy: </strong>".$row["subject"]."<br><strong>Üzenet: </strong>".$row["message"]."<br><strong>Idő: </strong><span class='mb-2'>".$row["message_date"]."</span><a href='delete-message.php?message=".$row["id"]."' class='mt-auto btn btn-secondary '>Törlés</a></div></br>";
                        }
                    } else {
                        echo "<strong class='pb-3'>Neked még nincs üzeneted!</strong>";
                    }
                ?>
                </div>
                <p class="mt-2 mb-0"><a href="index.php">Vissza</a></p>
            </form>  
        </div>
    </div>
</body>
</html>