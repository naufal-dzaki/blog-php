<?php
session_start();
include '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $email && $password) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header("Location: register.php?error=Email+tida+valid");
            exit;
        }

        $check = mysqli_prepare($koneksi, "SELECT user_id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($check, "s", $email);
        mysqli_stmt_execute($check);
        $result = mysqli_stmt_get_result($check);

        if (mysqli_fetch_assoc($result)) {
            header("Location: register.php?error=Email+telah+digunakan+sebelumnya");
            exit;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($koneksi, $query);
        mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashedPassword);

        if (mysqli_stmt_execute($stmt)) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = mysqli_insert_id($koneksi);
            $_SESSION['email'] = $email;
            header("Location: ../dashboard/index.php");
            exit;
        } else {
            header("Location: register.php?error=Registrasi+gagal.+Silakan+coba+lagi");
            exit;
        }
    } else {
        header("Location: register.php?error=Harap+isi+semua+field");
        exit;
    }
} else {
    echo "Akses tidak valid.";
}
