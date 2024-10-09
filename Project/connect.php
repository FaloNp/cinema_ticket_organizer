<?php
session_start();

if ((!isset($_POST["login"])) || (!isset($_POST["password"]))) {
	$_SESSION["login_error"] = "Nieprawidlowy login lub haslo";
	header("Location: index.php");
	exit(); //warunek sprawdza czy uzytkownik podal jakies dane logowania
}
require_once "data.php"; //zalacza pliki potrzebne do polaczenia z baza danych

$check = @new mysqli($dataName, $dataLogin, $dataPassword, $dataPath);
if ($check->connect_errno != 0) {
	echo "Blad: " . $check->connect_errno;
} else {
	$login = $_POST["login"];
	$haslo = $_POST["password"];
	$login = htmlentities($login, ENT_QUOTES, "UTF-8"); //usuwanie niebezpiecznych znakow
	if ($wynik = @$check->query(sprintf("SELECT* FROM uzytkownicy WHERE login = '%s' OR data = '%s' ", mysqli_real_escape_string($check, $login), mysqli_real_escape_string($check, $login)))) {
		$ile_wyniki = $wynik->num_rows; //sprawdza ile znalazlo uzytkownikow
		if ($ile_wyniki > 0) {
			$informacje = $wynik->fetch_assoc(); //wyciaga dene z bazy
			if (password_verify($haslo, $informacje["password"])) {
				$_SESSION["zalogowano"] = true; //uzytkownik zalogowal sie na profil
				$_SESSION["Login"] = $informacje["login"];
				$_SESSION["UserId"] = $informacje["id"];
				unset($_SESSION["blad"]);
				if ($informacje['id'] == 1) {
					$_SESSION["falo"] = true; //flaga uprawnien administratora
				}
				$wynik->close();
				header("Location: index.php");
			} else {
				$_SESSION["login_error"] = "Nieprawidlowy login lub haslo";
				header("Location: index.php");
			}
		} else {
			$_SESSION["login_error"] = "Nieprawidlowy login lub haslo";
			header("Location: index.php");
		}
		$wynik->close();
	}
	$check->close();
}
