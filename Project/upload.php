<?php
session_start();
if ((!isset($_SESSION["falo"])) || ($_SESSION["falo"] == false)) {
	header("Location: index.php");
	exit();
}

if (isset($_POST["uploadData"])) {
	$data_correct = true;

	$date = $_POST["date"];
	$name = $_POST["nazwa"];
	$description = $_POST["opis"];
	$ticket = $_POST["miejsca"];
	$time = $_POST["time"];
	if (empty($time)) {
		$_SESSION["error"] = "Time: Wpisz poprawne wartosci";
		header("Location: uploadrepertuar.php");
		exit();
	}
	if (empty($date)) {
		$_SESSION["error"] = "Date: Wpisz poprawne wartosci";
		header("Location: uploadrepertuar.php");
		exit();
	}
	if (empty($name)) {
		$_SESSION["error"] = "Name: Wpisz poprawne wartosci";
		header("Location: uploadrepertuar.php");
		exit();
	}
	if (empty($description)) {
		$_SESSION["error"] = "Description: Wpisz poprawne wartosci";
		header("Location: uploadrepertuar.php");
		exit();
	}
	if (empty($ticket)) {
		$_SESSION["error"] = "Ticket: Wpisz poprawne wartosci";
		header("Location: uploadrepertuar.php");
		exit();
	}



	if (preg_match("/[\'^$%&*()}{@#~?><>,|=_+-.]/", $name)) {
		$_SESSION["error"] = "Name: Wpisano bledna wartosc";
		header("Location: uploadrepertuar.php");
		exit();
	}
	if (preg_match("/[\'^$%&*()}{@#~?><>,|=_+-.]/", $description)) {
		$_SESSION["error"] = "Description: Wpisano bledna wartosc";
		header("Location: uploadrepertuar.php");
		exit();
	}
	if (preg_match("/[\'^$%&*()}{@#~?><>,|=_+.]/", $time)) {
		$_SESSION["error"] = "Time: Wpisano bledna wartosc";
		header("Location: uploadrepertuar.php");
		exit();
	}
	if (preg_match("/[a-z]/i", $ticket)) {
		$_SESSION["error"] = "Ticket: To nie liczba";
		header("Location: uploadrepertuar.php");
		exit();
	}


	if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] === UPLOAD_ERR_OK) {
		echo "<pre>";  //pobieranie danych do nastepnego etaptu
		print_r(($_FILES["foto"]));
		echo "</pre>";

		$photo_data = $_FILES["foto"]["name"];
		$photo_large = $_FILES["foto"]["size"];
		$photo_copy = $_FILES["foto"]["tmp_name"];
		$photo_error = $_FILES["foto"]["error"];

		if ($photo_error == 0) {
			if ($photo_large > 125000) {
				$_SESSION["photo_error"] = "Zbyt duzy plik";
				header("Location: uploadrepertuar.php");
				exit();
			}
			$photo_extension = pathinfo($photo_data, PATHINFO_EXTENSION); //pobiera rozszerzenie pliku
			$photo_validate = strtolower($photo_extension);
			$photo_access = array("png"); //przepuszcza pliki z tym formatem
			if (!in_array($photo_validate, $photo_access)) {
				$_SESSION["photo_error"] = "Nieprawidlowe rozszerzenie pliku";
				header("Location: uploadrepertuar.php");
				exit();
			}
			$photo_local = uniqid("Photo-", true) . "." . $photo_validate;
			$photo_path = "repertuar/Photo/" . $photo_local;
			move_uploaded_file($photo_copy, $photo_path);
		} else {
			$_SESSION["photo_error"] = "Uszkodzony plik";
			header("Location: uploadrepertuar.php");
			exit();
		}
	} else {
		$photo_local = "klocek.png";
	}

	require_once "data.php";
	try {
		$request = @new mysqli($dataName, $dataLogin, $dataPassword, $dataPath);
		if ($request->connect_errno != 0) {
			throw new Exception(mysqli_connect_errno());
		} else {
			$info_repertuar = $name; //. "." . $description;
			$id = 0;
			if ($request->query("INSERT INTO repertuar VALUES (NULL, '$date', '$info_repertuar', '$time','$photo_local')")) {
				$wynik = @$request->query(sprintf("SELECT * FROM repertuar WHERE date = '%s' AND name = '%s' AND time = '%s'", $date, $info_repertuar, $time));
				$ile_wyniki = $wynik->num_rows;
				while ($informacje = $wynik->fetch_assoc()) {
					$id = $informacje["id"];
				}
				if ($request->query("INSERT INTO miejsca VALUES (NULL, '$id', '$ticket','')")) {
					header("Location: uploadrepertuar.php");
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
		header("Location: uploadrepertuar.php");
		exit();
	}
} else {
	$_SESSION["error"] = "Dodaj repertuar";
	header("Location: uploadrepertuar.php");
	exit();
}
