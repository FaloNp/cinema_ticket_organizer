<?php
session_start();
if ((!isset($_SESSION["falo"])) || ($_SESSION["falo"] == false)) {
    header("Location: index.php");
    exit();
}
?>

<html lang="pl">

<head>
    <meta charset="utf-8" />
    <title>Teatr</title>
    <meta name="description" content="Teatr" />
    <meta name="keywords" content="teatr, rozrywka" />
    <meta name="author" content="Olaf" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <link rel="stylesheet" href="css/style.css" type="text/css" />
</head>

<body>
    <div class="container">
        <div class="rightcollumn">
            <form action="upload.php" method="post" enctype="multipart/form-data">
                <div class='row'>
                    <div class='rowHeader'>Nazwa: </div>
                    <input type="text" name="nazwa" placeholder="xyz" value="test" />
                </div>
                <div class='row'>
                    <div class='rowHeader'>Opis: </div>
                    <input type="text" name="opis" placeholder="xyz" value="test test" />
                </div>
                <div class='row'>
                    <div class='rowHeader'>Data: </div>
                    <input type="text" name="date" placeholder="YYYY-MM-DD" value="<?php echo date('Y-m-d'); ?>" />
                </div>
                <div class='row'>
                    <div class='rowHeader'>Time: </div>
                    <input type="text" name="time" placeholder="00-00" value="00-00" />
                </div>
                <div class='row'>
                    <div class='rowHeader'>Miejsca: </div>
                    <input type="text" name="miejsca" placeholder="0" value="10" />
                </div>
                <div class='row'>
                    <div class='rowHeader'>Dodaj zdjecie: </div>
                    <input type="file" name="foto" />
                </div>
                <div class='row'>
                    <div class='loginButton'><a href='index.php'>Powrot</a></div>
                    <input type="submit" name="uploadData" value="Zrobione" />
                </div>
            </form>
        </div>
        <div class="leftcollumn">
            <?php
            if (isset($_SESSION["error"])) {
                echo $_SESSION["error"];
                unset($_SESSION["error"]);
            }
            if (isset($_SESSION["photo_error"])) {
                echo $_SESSION["photo_error"];
                unset($_SESSION["photo_error"]);
            }
            ?>
        </div>
    </div>
</body>