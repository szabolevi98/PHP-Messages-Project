<!DOCTYPE html>
<html lang="en">
<?php include(realpath("./pages/")."/head.php"); ?>
<body class="text-center">
    <div class="container py-5">
        <div class="form bg-light shadow border rounded p-4">
            <?php
                if(isset($_GET["page"]) && !empty($_GET["page"]))
                {
                    if(file_exists(realpath("./pages/")."/".$_GET["page"].".php")) 
                    {
                        include(realpath("./pages/")."/".$_GET["page"].".php");
                    }
                    else {
                        include(realpath("./pages/")."/404.php");
                    }
                } else {
                    include(realpath("./pages/")."/login.php");
                }
            ?>
        </div>
    </div>
</body>
</html>