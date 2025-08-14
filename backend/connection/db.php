<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'wedding_prep';
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die('Koneksi gagal: ' . $conn->connect_error);
}
