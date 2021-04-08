<?php
    session_start();

    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: index.php?page=login");
        exit;
    }

    require_once "config.php";

    $sql = "SELECT
            messages.id,
            messages.user_id,
            messages.subject,
            messages.message,
            DATE_FORMAT(messages.message_date, '%Y.%m.%d. %H:%i') AS message_date,
            messages.answer,
            DATE_FORMAT(messages.answer_date, '%Y.%m.%d. %H:%i') AS answer_date,
            users.username
            FROM
            messages
            INNER JOIN users ON messages.user_id = users.id";
    if ($_SESSION["admin"] == false) {
        $sql .= " WHERE user_id = ".$_SESSION["id"]." ORDER BY message_date DESC;";
    } else {
        $sql .= " ORDER BY answer ASC, message_date ASC;";
    }
    
    $result = mysqli_query($connection, $sql);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if ($_SESSION["admin"] == true) {
            if (empty(trim($_POST["answer_textarea"]))) {
                echo  "<strong>Kérlek írj be egy választ!</strong>";
            } else {
                $answer_textarea = trim($_POST["answer_textarea"]);
            }

            if (empty(trim($_POST["answer_id"]))) {
                echo  "<strong>Hiányzik a válaszhoz tartozó ID!</strong>";
            } else {
                $answer_id = trim($_POST["answer_id"]);
            }
            
            if (!empty($answer_textarea) && !empty($answer_id)) {
                $sql = "UPDATE messages SET answer = ? WHERE id = ?";
                
                if ($stmt = mysqli_prepare($connection, $sql)) {
                    mysqli_stmt_bind_param($stmt, "si", $param_answer, $param_id);
                    
                    $param_answer = $answer_textarea;
                    $param_id = $answer_id;
                    
                    if (mysqli_stmt_execute($stmt)) {
                        header("location: index.php?page=view-message#".$answer_id."");
                    } else {
                        echo "<p><strong>Valami hiba történt, kérlek próbáld újra később!</strong></p>";
                    }
    
                    mysqli_stmt_close($stmt);
                }
            }
        } else {
            echo  "<strong>Nice try</strong>";
        }
    }
    
    mysqli_close($connection);
?>
<form class="form-signin">
    <h2 class="font-weight-normal">Üzenetek</h2>
    <?php 
        echo $_SESSION["admin"] == true ? "<p class='text-muted'>Te admin vagy, minden üzenetet látsz!</p>" : "<p class='text-muted'>Itt találod meg az üzeneteidet.</p>";
    ?>
    <div class="row justify-content-center">
    <?php
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                echo empty($row["answer"]) ? "<div class='col-auto md-1 card p-3 m-3' " : "<div class='col-auto md-1 card p-3 m-3 bg-answer' ";
                echo    "id='".$row["id"]."'>";
                if ($_SESSION["admin"] == true) {
                    echo    "<strong>Küldte: </strong>
                            ".$row["username"]."<br>";
                }
                echo    "<strong>Tárgy: </strong>
                        ".$row["subject"]."<br>
                        <strong>Üzenet: </strong>
                        ".$row["message"]."<br>
                        <span class='font-italic'>".$row["message_date"]."</span>
                        <hr>";
                if (!empty($row["answer"])) {
                    echo    "<strong>Válasz: </strong>
                            ".$row["answer"]."<br>
                            <span class='font-italic'>".$row["answer_date"]."</span>
                            <hr>";
                }
                if ($_SESSION["admin"] == true) {
                    echo    "<form action='".htmlspecialchars($_SERVER['PHP_SELF'])."?page=view-message' method='post'>
                                <strong>Válasz: </strong>
                                <div class='form-group'>
                                    <input type='hidden' name='answer_id' id='answer_id' class='form-control' value='".$row["id"]."'>
                                </div>  
                                <div class='form-group'>
                                    <textarea rows='3' name='answer_textarea' id='answer_textarea' placeholder='Ide írhatod a választ.' class='form-control'>".$row["answer"]."</textarea>
                                </div>
                                <div class='form-group mb-0'>
                                    <input type='submit' class='btn btn-primary btn-block' value='";
                                    echo empty($row["answer"]) ? "Válaszol" : "Válasz frissítése";
                                echo "'></div>
                            </form>
                            <hr>";
                }
                echo    "<a href='index.php?page=delete-message&message=".$row["id"]."' class='mt-auto btn btn-secondary '>Törlés</a>
                        </div>
                        </br>";
            }
        } else {
            echo "<strong class='pb-3'>Neked még nincs üzeneted!</strong>";
        }
    ?>
    </div>
    <p class="mt-2 mb-0"><a href="index.php?page=home">Vissza</a></p>
</form>  
