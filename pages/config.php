<?php
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'db_example');
    
    $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if($connection === false) {
        die("<h1>Nem sikerült kapcsolódni az adatbázishoz!</h1>" . mysqli_connect_error());
    }
?>
