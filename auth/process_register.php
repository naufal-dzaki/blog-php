<?php
session_start();
include '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Ambil dan filter input
    $username = htmlspecialchars($_POST['username'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validasi input tidak kosong
    if ($username && $email && $password) {
        // Cek apakah email sudah terdaftar
        $check = mysqli_prepare($koneksi, "SELECT user_id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($check, "s", $email);
        mysqli_stmt_execute($check);
        $result = mysqli_stmt_get_result($check);

        if (mysqli_fetch_assoc($result)) {
            echo "Email sudah terdaftar.";
            exit;
        }

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Simpan user baru
        $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($koneksi, $query);
        mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashedPassword);

        if (mysqli_stmt_execute($stmt)) {
            // Simpan session dan redirect
            $_SESSION['user_id'] = mysqli_insert_id($koneksi);
            $_SESSION['email'] = $email;
            header("Location: ../dashboard/index.php");
            exit;
        } else {
            echo "Registrasi gagal. Silakan coba lagi.";
        }
    } else {
        echo "Harap isi semua field.";
    }
} else {
    echo "Akses tidak valid.";
}
