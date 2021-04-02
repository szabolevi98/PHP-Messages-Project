<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="en">
<?php $title = "Index"; include "head.php"; ?>
<body class="text-center">
    <div class="container py-5">
        <div class="form bg-light shadow border rounded p-4">
            <h2 class="font-weight-normal mb-1">Üdvözöllek</h1>
            <h4 class="font-weight-normal"><?php echo htmlspecialchars($_SESSION["username"]); ?></h4>
            <p><b>Email címed:</b> <?php echo htmlspecialchars($_SESSION["email"]); ?><br>
            <b>Regisztráltál:</b> <?php echo htmlspecialchars($_SESSION["register_date"]); ?></p>
            <a href="reset-password.php" class="btn btn-primary btn-block">Jelszó változtatása</a>
            <a href="send-message.php" class="btn btn-info btn-block">Üzenet írása</a>
            <a href="view-message.php" class="btn btn-secondary btn-block">Üzeneteim</a>
            <a href="logout.php" class="btn btn-danger btn-block">Kijelentkezés</a>
        </div>
    </div>
</body>
</html>