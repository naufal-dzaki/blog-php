<?php
session_start();
include './db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $article_id = $_POST['id'];
    $user_id = $_SESSION['user_id'];

    $query = "DELETE FROM articles WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "ii", $article_id, $user_id);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Gagal menghapus artikel.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Metode tidak diizinkan']);
}
