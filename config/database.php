<?php
// config/database.php

$host = "localhost";
$user = "root";
$password = "";
$database = "ev_shop";

// $host = "sql306.infinityfree.com";
// $user = "if0_40870720";
// $password = "fe9AKUBWEB";
// $database = "if0_40870720_ev_shop";


$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Database connection failed");
}

mysqli_set_charset($conn, "utf8mb4");
