<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "simple_dashboard";

$connection = mysqli_connect($host, $user, $password, $database);
if (!$connection) {
  die("Database connect failed");
}