<?php
    session_start();

    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: login.php");
        exit;
    }

    require_once "config.php";
    $new_password = $confirm_password = "";
    $new_password_err = $confirm_password_err = "";
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty(trim($_POST["new_password"]))) {
            $new_password_err = "Kérlek adj meg egy jelszót!";     
        } elseif (strlen(trim($_POST["new_password"])) < 5) {
            $new_password_err = "A jelszavadnak legalább 5 karakternek kell lennie!";
        } else {
            $new_password = trim($_POST["new_password"]);
        }

        if (empty(trim($_POST["confirm_password"]))) {
            $confirm_password_err = "Kérlek erősítsd meg a jelszavad!";
        } else {
            $confirm_password = trim($_POST["confirm_password"]);
            if(empty($new_password_err) && ($new_password != $confirm_password)){
                $confirm_password_err = "A jelszavak nem egyeznek!";
            }
        }

        if (empty($new_password_err) && empty($confirm_password_err)) {
            $sql = "UPDATE users SET password = ? WHERE id = ?";
            if ($stmt = mysqli_prepare($connection, $sql)) {
                mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);
                $param_password = password_hash($new_password, PASSWORD_DEFAULT);
                $param_id = $_SESSION["id"];
                if(mysqli_stmt_execute($stmt)){
                    session_destroy();
                    header("location: login.php");
                    exit();
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
<?php $title = "Jelszó változtatása"; include "head.php"; ?>
<body class="text-center">
    <div class="container py-5">
        <div class="form bg-light shadow border rounded p-4">
            <form class="form-signin" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <h2 class="font-weight-normal">Jelszó változtatása</h2>
                <p class="text-muted">Kérlek add meg az új jelszót kétszer.</p>
                <div class="form-group">
                    <input type="password" name="new_password" placeholder="Új jelszó" class="form-control <?php echo (!empty($new_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_password; ?>">
                    <span class="invalid-feedback"><?php echo $new_password_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="password" name="confirm_password" placeholder="Új jelszó újra" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary btn-block" value="Változtat">
                </div>
                <p class="mb-0"><a href="index.php">Mégse</a></p>
            </form>  
        </div>
    </div>
</body>
</html>