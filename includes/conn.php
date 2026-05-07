<?php 
$conn = new mysqli("localhost", "root", "", "beauty_care");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}