<?php
include '../includes/db.php';
include '../includes/check_credential.php';

$id = $_GET['id'] ?? 0;
$article = null;
$message = '';
$messageType = '';

if ($id) {
    $query = "SELECT * FROM articles WHERE id = " . intval($id);
    $result = mysqli_query($koneksi, $query);
    $article = mysqli_fetch_assoc($result);
}

if (!$article) {
    echo "<script>alert('Artikel tidak ditemukan!'); window.location.href = 'index.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $title = mysqli_real_escape_string($koneksi, $_POST['title']);
    $content = mysqli_real_escape_string($koneksi, $_POST['content']);
    $current_image = $article['image'];
    $image = $current_image;
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
        $new_filename = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
        if (in_array(strtolower($file_extension), $allowed_types)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                if ($current_image && file_exists($target_dir . $current_image)) {
                    unlink($target_dir . $current_image);
                }
                $image = $new_filename;
            }
        }
    }
    
    if (isset($_POST['remove_image']) && $_POST['remove_image'] == '1') {
        if ($current_image && file_exists($target_dir . $current_image)) {
            unlink($target_dir . $current_image);
        }
        $image = '';
    }
    
    $query = "UPDATE articles SET 
              user_id = '$user_id', 
              title = '$title', 
              content = '$content', 
              image = '$image',
              updated_at = CURRENT_TIMESTAMP
              WHERE id = " . intval($id);
    
    if (mysqli_query($koneksi, $query)) {
        $message = 'Artikel berhasil diupdate!';
        $messageType = 'success';
        $result = mysqli_query($koneksi, "SELECT * FROM articles WHERE id = " . intval($id));
        $article = mysqli_fetch_assoc($result);
        
        echo "<script>
                setTimeout(function() {
                    window.location.href = 'detail.php?id=" . $id . "';
                }, 2000);
              </script>";
    } else {
        $message = 'Error: ' . mysqli_error($koneksi);
        $messageType = 'danger';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Artikel: <?php echo htmlspecialchars($article['title']); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href=".././css/styles.min.css" />
</head>
<body class="bg-light">
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical">
        <aside class="left-sidebar">
            <div>
                <div class="brand-logo d-flex align-items-center justify-content-between">
                    <a href="../index.html" class="text-nowrap logo-img" style="font-size: 1.5rem; font-weight: bold;">
                        Ruangku
                    </a>
                    <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                        <i class="ti ti-x fs-6"></i>
                    </div>
                </div>
                <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
                    <ul id="sidebarnav">
                        <li class="nav-small-cap">
                        <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
                        <span class="hide-menu">Home</span>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link justify-content-between"  
                            href="#">
                            <div class="d-flex align-items-center gap-3">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                <i class="ti ti-chart-donut-3"></i>
                                </div>
                                <span class="hide-menu">Blog</span>
                            </div>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>  
        <div class="body-wrapper">
            <div class="body-wrapper-inner">
                <div class="container-fluid" style="padding-top: 56px;">
                    <div class="card">
                        <div class="card-body">
                        <div class="pb-4 d-flex justify-content-between align-items-center">
                            <h2>Edit Artikel</h2>
                        </div>
                        <div class="card-body">
                            <?php if ($message): ?>
                                <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                                    <?php echo $message; ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php endif; ?>
                            
                            <form method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label class="form-label">Gambar Saat Ini</label>
                                    <?php if ($article['image']): ?>
                                        <div class="mb-2">
                                            <img src="../uploads/<?php echo htmlspecialchars($article['image']); ?>" 
                                                alt="Current image" class="img-thumbnail" style="max-width: 200px;">
                                            <div class="form-check mt-2">
                                                <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image" value="1">
                                                <label class="form-check-label text-danger" for="remove_image">
                                                    <i class="fas fa-trash me-1"></i>Hapus gambar ini
                                                </label>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-muted"><em>Tidak ada gambar</em></p>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="image" class="form-label">Upload Gambar Baru (Opsional)</label>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                    <div class="form-text">Format yang didukung: JPG, JPEG, PNG, GIF. Akan mengganti gambar lama jika ada.</div>
                                </div>

                                <div class="mb-3">
                                    <label for="title" class="form-label">Judul Artikel</label>
                                    <input type="text" class="form-control" id="title" name="title" 
                                        value="<?php echo htmlspecialchars($article['title']); ?>" required maxlength="255">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="content" class="form-label">Konten Artikel</label>
                                    <textarea class="form-control" id="content" name="content" rows="10" required><?php echo htmlspecialchars($article['content']); ?></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        <strong>Terakhir diupdate:</strong> 
                                        <?php 
                                        if ($article['updated_at']) {
                                            echo date('d F Y, H:i', strtotime($article['updated_at']));
                                        } else {
                                            echo 'Belum pernah diupdate';
                                        }
                                        ?>
                                    </small>
                                </div>
                                
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="detail.php?id=<?php echo $article['id']; ?>" class="btn btn-secondary me-md-2">
                                        <i class="fas fa-arrow-left me-1"></i>Kembali
                                    </a>
                                    <a href="index.php" class="btn btn-info me-md-2">
                                        <i class="fas fa-list me-1"></i>Daftar Artikel
                                    </a>
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-save me-1"></i>Update Artikel
                                    </button>
                                </div>
                            </form>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div>
    
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script src=".././js/sidebarmenu.js"></script>
  <script src=".././js/app.min.js"></script>
  <script src=".././libs/simplebar/dist/simplebar.js"></script>
  <script src=".././js/dashboard.js"></script>
  
  <!-- Solar icons -->
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>
</html>