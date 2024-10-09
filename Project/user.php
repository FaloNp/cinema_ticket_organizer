<?php
session_start();

if (isset($_POST["nazwa"])) {
	//Wszystkie dane sie zgadzaja
	$data_correct = true;
	/////////////////////////////////////////////////////////// WARUNKI FOLMULARZA ////////////////////////////////////////////////////////////
	$nazwa = $_POST["nazwa"]; //Sprawdz poprawność nazwy
	if ((strlen($nazwa) < 4) || (strlen($nazwa) > 14)) {
		$data_correct = false; //przedrostek qy = blad
		$_SESSION["error_login"] = "Nick powinien zawierać od 4 do 14 znakow"; //nie prawidlowy formularz 
		header("Location: index.php");
	}
	if (ctype_alnum($nazwa) == false) {
		$data_correct = false; //Sprawdz czy wszystkie znaki sa dozwolone
		$_SESSION["error_login"] = "Uzyto niedozwolonych znakow ";
		header("Location: index.php");
	}

	//Sprawdz czy podany email jest prawidlowy
	$r = $_POST["email"]; //podany email (nizej)
	$ri = filter_var($r, FILTER_SANITIZE_EMAIL); //podany email po sprawdzeniu
	if ((filter_var($r, FILTER_VALIDATE_EMAIL) == false) || ($ri != $r)) {
		$data_correct = false; //porownywanie otrzymanego email z tym co byl na poczatku
		$_SESSION["error_r"] = "Podaj poprawny ";
		header("Location: index.php");
	}

	//Sprawdz podane haslo 
	$haslo = $_POST["haslo"];
	$q_haslo = $_POST["powtorz_haslo"];
	if ((strlen($haslo) <= 9) || (strlen($haslo) >= 40)) {
		$data_correct = false;
		$_SESSION["error_password"] = "Haslo powininno zawierać od 10 do 41 znakow";
		header("Location: index.php"); //nie prawidlowy formularz
	}
	if ($haslo != $q_haslo) {
		$data_correct = false; //Sprawdz zgodnosc
		$_SESSION["error_passwordcheck"] = "Podano inne haslo";
		header("Location: index.php");
	}
	$haslo_hash = password_hash($haslo, PASSWORD_DEFAULT);

	//Zasady
	if (!isset($_POST["zasady"])) {
		$data_correct = false;
		$_SESSION["error_zasady"] = "Wymagane jest zaakceptowanie regulaminu";
		header("Location: index.php");
	}

	////////////////////////////////////////////Zapamietaj dane//////////////////////////////////////////////////////////////////////////////////////////
	$_SESSION["backup_login"] = $nazwa;  //backup danych
	$_SESSION["backup_r"] = $r;
	////////////////////////////////////////////Zapamietaj dane//////////////////////////////////////////////////////////////////////////////////////////

	///////////////////////////////////////////sprawdzanie czy podane dane sie nie powtarzaja////////////////////////////////////////////
	require_once "data.php";
	try {
		$check = @new mysqli($dataName, $dataLogin, $dataPassword, $dataPath);
		if ($check->connect_errno != 0) {
			throw new Exception(mysqli_connect_errno());
		}
		/////////////////////////////////NAZWA///////////////////////////////////////////
		else {
			$wynik = $check->query("SELECT id FROM uzytkownicy WHERE login = '$nazwa' "); //Czy dane juz istnieja?
			if (!$wynik) throw new Exception($check->error);
			$ile_nazw = $wynik->num_rows;
			if ($ile_nazw == 1) {
				$data_correct = false;
				$_SESSION["error_login"] = "Istnieje juz taka nazwa, wybierz inna";
				header("Location: index.php");
			}
			$wynik = $check->query("SELECT id FROM uzytkownicy WHERE data = '$r' "); //Czy dane juz istnieja?
			if (!$wynik) throw new Exception($check->error);
			$ile_r = $wynik->num_rows;
			if ($ile_r == 1) {
				$data_correct = false;
				$_SESSION["error_r"] = "Istnieje juz, wybierz inny";
				header("Location: index.php");
			}

			if ($data_correct == true) {
				//Udalo
				if ($check->query("INSERT INTO uzytkownicy VALUES (NULL, '$nazwa', '$haslo_hash', '$r', '0')")) {
					$_SESSION["udanarejestracja"] = "Konto zostalo utworzone, zaloguj sie na swoje konto";
					header("Location: index.php");
				} else {
					throw new Exception($check->error);
				}
			}
			$check->close(); //zakoncz 
		}
		/////////////////////////////////NAZWA///////////////////////////////////////////
	} catch (Exception $error) //zlap wyjatek 
	{
		echo "Serwer w tym momencie jest wylaczony, prosimy o zarejestrowanie sie pozniej"; //dla uzytkownika
		echo "<br /> Dokladna informacja: " . $error;
	}
	///////////////////////////////////////////sprawdzanie czy podane dane sie nie powtarzaja////////////////////////////////////////////

	/////////////////////////////////////////////////////////// WARUNKI FOLMULARZA ////////////////////////////////////////////////////////////
}
