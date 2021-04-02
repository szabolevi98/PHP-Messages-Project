<?php
    session_start();

    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: login.php");
        exit;
    }

    require_once "config.php";
    $subject = $message = "";
    $subject_err = $message_err = "";
    $message_sent = "";
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty(trim($_POST["subject"]))) {
            $subject_err = "Kérlek adj meg egy tárgyat!";     
        } elseif (strlen(trim($_POST["subject"])) < 5) {
            $subject_err = "A tárgynak legalább 5 karakternek kell lennie!";
        } else {
            $subject = $_POST["subject"];
        }

        if (empty(trim($_POST["message"]))) {
            $message_err = "Kérlek írd be az üzeneted!";     
        } elseif (strlen(trim($_POST["message"])) < 10) {
            $message_err = "Az üzenetnek legalább 10 karakternek kell lennie!";
        } else {
            $message = $_POST["message"];
        }

        if (empty($subject_err) && empty($message_err)) {
            $sql = "INSERT INTO messages (user_id, subject, message) VALUES (?, ?, ?)";
            
            if ($stmt = mysqli_prepare($connection, $sql)) {
                mysqli_stmt_bind_param($stmt, "iss", $param_user_id, $param_subject, $param_message);
                
                $param_user_id = $_SESSION["id"];
                $param_subject = $subject;
                $param_message = $message;
                
                if (mysqli_stmt_execute($stmt)) {
                    $subject = "";
                    $message = "";
                    $message_sent = "Üzenet sikeresen elküldve!";
                } else {
                    echo "<p><strong>Valami hiba történt, kérlek próbáld újra később!</strong></p>";
                }

                mysqli_stmt_close($stmt);
            }
        }
        
        mysqli_close($connection);
    }
?>
 
<!DOCTYPE html>
<html lang="en">
<?php $title = "Üzenet írása"; include "head.php"; ?>
<body class="text-center">
    <div class="container py-5">
        <div class="form bg-light shadow border rounded p-4">
            <form class="form-signin" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <h2 class="font-weight-normal">Üzenet írása</h2>
                <p class="text-muted">Kérlek írd meg az üzeneted.</p>
                <?php 
                    if(!empty($message_sent)){
                        echo '<div class="alert alert-success mb-3">' . $message_sent . '</div>';
                    }        
                ?>
                <div class="form-group">
                    <input type="text" name="subject" placeholder="Üzenet tárgya" class="form-control <?php echo (!empty($subject_err)) ? 'is-invalid' : ''; ?>" <?php echo $subject_err; ?>" value="<?php echo $subject; ?>">
                    <span class="invalid-feedback"><?php echo $subject_err; ?></span>
                </div>
                <div class="form-group">
                    <textarea rows="3" name="message" id="message" placeholder="Ide írhatod az üzenetet" class="form-control <?php echo (!empty($message_err)) ? 'is-invalid' : ''; ?>"><?php echo $message; ?></textarea>
                    <span class="invalid-feedback"><?php echo $message_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary btn-block" value="Elküld">
                </div>
                <p class="mb-0"><a href="index.php">Vissza</a></p>
            </form>  
        </div>
    </div>
</body>
</html>