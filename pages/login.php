<?php
    session_start();
    
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
        header("location: index.php?page=home");
        exit;
    }

    require_once "config.php";
    
    $username = $password = "";
    $username_err = $password_err = $login_err = "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(empty(trim($_POST["username"]))){
            $username_err = "Kérlek add meg a felhasználóneved.";
        } else{
            $username = trim($_POST["username"]);
        }
        
        if(empty(trim($_POST["password"]))){
            $password_err = "Kérlek add meg a jelszavad.";
        } else{
            $password = trim($_POST["password"]);
        }
        
        if (empty($username_err) && empty($password_err)) {
            $sql = "SELECT id, username, password, email, register_date, admin FROM users WHERE username = ?";
            if ($stmt = mysqli_prepare($connection, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $param_username);
                $param_username = $username;

                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);

                    if (mysqli_stmt_num_rows($stmt) == 1) {                    
                        mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password, $email, $register_date, $admin);
                        
                        if (mysqli_stmt_fetch($stmt)){
                            if (password_verify($password, $hashed_password)) {
                                session_start();
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["username"] = $username;
                                $_SESSION["email"] = $email;  
                                $_SESSION["register_date"] = $register_date;        
                                $_SESSION["admin"] = $admin;                     
                                header("location: index.php?page=login");
                            } else {
                                $login_err = "Hibás felhasználónév vagy jelszó!";
                            }
                        }
                    } else {
                        $login_err = "A felhasználónév nem létezik.";
                    }
                } else {
                    echo "<p><strong>Valami hiba történt, kérlek próbáld újra később!</strong></p>";
                }

                mysqli_stmt_close($stmt);
            }
        }
        mysqli_close($connection);
    }
?>
<form class="form-signin" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?page=login" method="post">
    <h2 class="font-weight-normal">Bejelentkezés</h2>
    <p class="text-muted">Az űrlap kitöltésével tudsz bejelentkezni.</p>
    <?php 
        if(!empty($login_err)){
            echo '<div class="alert alert-danger mb-3">' . $login_err . '</div>';
        }
        if (isset($_GET["success"])) {
            echo '<div class="alert alert-success mb-3">Regisztráció sikerült, jelentkezz be.</div>';
        }
        if (isset($_GET["reset"])) {
            echo '<div class="alert alert-success mb-3">Jelszavad módosult, jelentkezz be újra!</div>';
        }
    ?>
    <div class="form-group">
        <input type="text" name="username" placeholder="Felhasználónév" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
        <span class="invalid-feedback"><?php echo $username_err; ?></span>
    </div>
    <div class="form-group">
        <input type="password" name="password" placeholder="Jelszó" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
        <span class="invalid-feedback"><?php echo $password_err; ?></span>
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-primary btn-block" value="Bejelentkezés">
    </div>
    <p class="mb-0">Még nincs felhasználód? <a href="index.php?page=register">Regisztrálj most</a>.</p>
</form>
