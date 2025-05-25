<?php
session_start();
include './db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];

$query = "SELECT id, title, created_at, updated_at FROM articles WHERE user_id = ? ORDER BY created_at DESC";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $created_date = date('d/m/Y H:i', strtotime($row['created_at']));
    $updated_date = $row['updated_at'] ? date('d/m/Y H:i', strtotime($row['updated_at'])) : '-';
    
    $actions = '
        <div class="btn-group" role="group">
            <a href="detail.php?id=' . $row['id'] . '" class="btn btn-info btn-sm" title="Detail">
                Detail
            </a>
            <a href="edit.php?id=' . $row['id'] . '" class="btn btn-warning btn-sm" title="Edit">
                Edit
            </a>
            <button class="btn btn-danger btn-sm" onclick="confirmDelete(' . $row['id'] . ')" title="Hapus">
                Delete
            </button>
        </div>';
    
    $data[] = [
        $row['id'],
        htmlspecialchars($row['title']),
        $created_date,
        $updated_date,
        $actions
    ];
}

echo json_encode(['data' => $data]);
