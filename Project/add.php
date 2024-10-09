<?php
session_start();
if ((!isset($_SESSION["zalogowano"])) || ($_SESSION["zalogowano"] == false)) {
    header("Location: index.php");
    exit();
}
if (isset($_POST["ticketadd"])) {
    $iduser = $_SESSION['UserId'];
    $idrepertuar = $_SESSION["idfocus"];
    $idticket =  $_SESSION["ticketfocus"];
    unset($_SESSION["idfocus"]);
    unset($_SESSION["ticketfocus"]);
    echo "q";
    require_once "data.php";
    try {
        $request = @new mysqli($dataName, $dataLogin, $dataPassword, $dataPath);
        if ($request->connect_errno != 0) {
            throw new Exception(mysqli_connect_errno());
        } else {
            $blocked = 0;
            if ($request->query("INSERT INTO data VALUES (NULL, '$iduser', '$idrepertuar', '$idticket')")) {
                $wynik = @$request->query(sprintf("SELECT * FROM miejsca WHERE repertuarid = '%s'", $idrepertuar));
                $ile_wyniki = $wynik->num_rows;
                while ($informacje = $wynik->fetch_assoc()) {
                    $blocked = $informacje["blocked"] . "." . $idticket;
                }
                echo $blocked;
                if ($request->query("UPDATE miejsca SET blocked = $blocked WHERE  	repertuarid = $idrepertuar")) {
                    header("Location: index.php");
                } else {
                    throw new Exception($request->error);
                }
            } else {
                throw new Exception($request->error);
            }
        }
        $request->close();
    } catch (Exception $error) {
        $_SESSION["error"] = $error;
        header("Location: index.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
