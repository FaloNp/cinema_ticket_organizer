<?php
function RepertuarTable()
{
    require "data.php";
    $tablename = "repertuar";
    $table = "CREATE TABLE $tablename (
        id INT PRIMARY KEY AUTO_INCREMENT,
        date DATE,
        name TEXT,
        time TEXT,
        photo BLOB
    )";
    $connection = new mysqli($dataName, $dataLogin, $dataPassword, $dataPath);
    if ($connection->query($table) === TRUE) {
        echo "Tabela $tablename została utworzona pomyślnie.";
    } else {
        echo "Błąd podczas tworzenia tabeli: " . $connection->error;
        echo "Kod błędu: " . $connection->errno;
    }
}

function UzytkownicyTable()
{
    require "data.php";
    $tablename = "uzytkownicy";
    $table = "CREATE TABLE $tablename (
        id INT PRIMARY KEY AUTO_INCREMENT,
        login TEXT,
        password TEXT,
        data TEXT,
        ip TEXT
    )";
    $connection = new mysqli($dataName, $dataLogin, $dataPassword, $dataPath);
    if ($connection->query($table) === TRUE) {
        echo "Tabela $tablename została utworzona pomyślnie.";
    } else {
        echo "Błąd podczas tworzenia tabeli: " . $connection->error;
        echo "Kod błędu: " . $connection->errno;
    }
}

function DataTable()
{
    require "data.php";
    $tablename = "data";
    $table = "CREATE TABLE $tablename (
        id INT PRIMARY KEY AUTO_INCREMENT,
        userid INT,
        repertuarid INT,
        ticketid INT
    )";
    $connection = new mysqli($dataName, $dataLogin, $dataPassword, $dataPath);
    if ($connection->query($table) === TRUE) {
        echo "Tabela $tablename została utworzona pomyślnie.";
    } else {
        echo "Błąd podczas tworzenia tabeli: " . $connection->error;
        echo "Kod błędu: " . $connection->errno;
    }
}

function MiejscaTable()
{
    require "data.php";
    $tablename = "miejsca";
    $table = "CREATE TABLE $tablename (
        id INT PRIMARY KEY AUTO_INCREMENT,
        repertuarid INT,
        ticket INT,
        blocked TEXT
    )";
    $connection = new mysqli($dataName, $dataLogin, $dataPassword, $dataPath);
    if ($connection->query($table) === TRUE) {
        echo "Tabela $tablename została utworzona pomyślnie.";
    } else {
        echo "Błąd podczas tworzenia tabeli: " . $connection->error;
        echo "Kod błędu: " . $connection->errno;
    }
}

function UzytkownicyTableExists()
{
    require "data.php";
    $tablename = "uzytkownicy";
    $connection = new mysqli($dataName, $dataLogin, $dataPassword, $dataPath);
    $result = $connection->query("SHOW TABLES LIKE '$tablename'");
    return $result->num_rows > 0;
}

function RepertuarTableExists()
{
    require "data.php";
    $tablename = "repertuar";
    $connection = new mysqli($dataName, $dataLogin, $dataPassword, $dataPath);
    $result = $connection->query("SHOW TABLES LIKE '$tablename'");
    return $result->num_rows > 0;
}
function ResetTable()
{
    require "data.php";
    $connection = new mysqli($dataName, $dataLogin, $dataPassword, $dataPath);
    $connection->query("
    SET FOREIGN_KEY_CHECKS = 0; 
    SET @tables = NULL;
    SELECT GROUP_CONCAT('`', table_schema, '`.`', table_name, '`') INTO @tables
    FROM information_schema.tables 
    WHERE table_schema = $dataPath;

    SET @tables = CONCAT('DROP TABLE ', @tables);
    PREPARE stmt FROM @tables;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    SET FOREIGN_KEY_CHECKS = 1;");
}
ResetTable();
RepertuarTable();
UzytkownicyTable();
MiejscaTable();
DataTable();
