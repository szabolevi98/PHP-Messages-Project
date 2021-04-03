<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="style/main.css">
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico" />
    <?php
        $page = isset($_GET["page"]) ? $_GET["page"] : "";
        switch($page){
            case "home":
                $title = "Felhasználói panel";
                break;
            case "register":
                $title = "Regisztráció";
                break;
            case "login":
                $title = "Bejelentkezés";
                break;
            case "reset-password":
                $title = "Jelszó változtatása";
                break;
            case "send-message":
                $title = "Üzenet írása";
                break;
            case "view-message":
                $title = "Üzenetek megtekintése";
                break;
            case "view-message":
                    $title = "Üzenetek megtekintése";
                    break;
            default:
                $title = "404";

        }
        $title .= " - PHP Messages Project";
    ?>
    <title><?php echo htmlspecialchars($title)?></title>
</head>