<?php
    session_start();
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: index.php?page=login");
        exit;
    }
?>
<div class="home">
    <h2 class="font-weight-normal mb-1">Üdvözöllek</h1>
    <h4 class="font-weight-normal"><?php echo htmlspecialchars($_SESSION["username"]); ?></h4>
    <p><strong>Email címed:</strong> <?php echo htmlspecialchars($_SESSION["email"]); ?><br>
    <strong>Regisztráltál:</strong> <?php echo htmlspecialchars($_SESSION["register_date"]); ?>
    <?php 
        if ($_SESSION["admin"] == true) {
            echo "<br><strong>Rendszergazda jogosultság:</strong> Igen";
        } /* else {
            echo "<br><strong>Rendszergazda jogosultság:</strong> Nem";
        } */
    ?>
    </p>
    <a href="index.php?page=reset-password" class="btn btn-primary btn-block">Jelszó változtatása</a>
    <a href="index.php?page=send-message" class="btn btn-info btn-block">Üzenet írása</a>
    <a href="index.php?page=view-message" class="btn btn-secondary btn-block">
    <?php echo $_SESSION["admin"] == true ? "Üzenetek kezelése" : "Üzeneteim"; ?>
    </a>
    <a href="index.php?page=logout" class="btn btn-danger btn-block">Kijelentkezés</a>
</div>
