<?php
    session_start();
    
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
        header("location: index.php");
        exit;
    }

    require_once "config.php";
    $username = $email = $password = $confirm_password = "";
    $username_err = $email_err = $password_err = $confirm_password_err = "";
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty(trim($_POST["username"]))) {
            $username_err = "Kérlek adj meg egy felhasználónevet.";
        } elseif (strlen(trim($_POST["username"])) < 3) {
            $username_err = "A felhasználó névnek legalább 3 karakternek kell lennie!";
        } else {
            $sql = "SELECT id FROM users WHERE username = ?";
            
            if ($stmt = mysqli_prepare($connection, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $param_username);
                $param_username = trim($_POST["username"]);
                
                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);
                    
                    if (mysqli_stmt_num_rows($stmt) == 1) {
                        $username_err = "Ez a felhasználónév már foglalt!";
                    } else {
                        $username = trim($_POST["username"]);
                    }
                } else {
                    echo "<p><strong>Valami hiba történt, kérlek próbáld újra később!</strong></p>";
                }

                mysqli_stmt_close($stmt);
            }
        }

        if (empty(trim($_POST["email"]))) {
            $email_err = "Kérlek adj meg egy email!";     
        } elseif (strlen(trim($_POST["email"])) < 6) {
            $email_err = "Az email címnek legalább 6 karakternek kell lennie!";
        } else {
            $email = trim($_POST["email"]);
        }
        
        if (empty(trim($_POST["password"]))) {
            $password_err = "Kérlek adj meg egy jelszót!.";     
        } elseif (strlen(trim($_POST["password"])) < 5) {
            $password_err = "A jelszónak legalább 5 karakternek kell lennie!";
        } else {
            $password = trim($_POST["password"]);
        }
        
        if (empty(trim($_POST["confirm_password"]))) {
            $confirm_password_err = "Kérlek erősítsd meg a jelszót!";     
        } else {
            $confirm_password = trim($_POST["confirm_password"]);
            if (empty($password_err) && ($password != $confirm_password)) {
                $confirm_password_err = "A jelszavak nem egyeznek!";
            }
        }
        
        if (empty($username_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)) {
            $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            
            if ($stmt = mysqli_prepare($connection, $sql)) {
                mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_email, $param_password);
                
                $param_username = $username;
                $param_email = $email;
                $param_password = password_hash($password, PASSWORD_DEFAULT);
                
                if (mysqli_stmt_execute($stmt)) {
                    header("location: login.php?success=true");
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
<?php $title = "Regisztráció"; include "head.php"; ?>
<body class="text-center">
    <div class="container py-5">
        <div class="form bg-light shadow border rounded p-4">
            <form class="form-signin" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <h2 class="font-weight-normal">Regisztráció</h2>
                <p class="text-muted">Regisztráláshoz kérlek töltsd ki az űrlapot.</p>
                <div class="form-group">
                    <input type="text" name="username" placeholder="Felhasználónév" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                    <span class="invalid-feedback"><?php echo $username_err; ?></span>
                </div>    
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                    <span class="invalid-feedback"><?php echo $email_err; ?></span>
                </div>    
                <div class="form-group">
                    <input type="password" name="password" placeholder="Jelszó" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                    <span class="invalid-feedback"><?php echo $password_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="password" name="confirm_password" placeholder="Jelszó újra" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                    <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary btn-block" value="Elküld">
                </div>
                <p class="mb-0">Már van felhasználói fiókod? <a href="login.php">Jelentkezz be</a>.</p>
            </form>
        </div>
    </div>
</body>
</html>