<?php
include '../includes/db.php';
include '../includes/check_credential.php';


$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $title = mysqli_real_escape_string($koneksi, $_POST['title']);
    $content = mysqli_real_escape_string($koneksi, $_POST['content']);
    $image = '';
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
        $new_filename = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
        if (in_array(strtolower($file_extension), $allowed_types)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image = $new_filename;
            }
        }
    }
    
    $query = "INSERT INTO articles (user_id, title, content, image) VALUES ('$user_id', '$title', '$content', '$image')";
    
    if (mysqli_query($koneksi, $query)) {
        $message = 'Artikel berhasil ditambahkan!';
        $messageType = 'success';
        echo "<script>
                setTimeout(function() {
                    window.location.href = 'index.php';
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
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard | Artikel</title>
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
                        <h2>Tambah Artikel</h2>
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
                                <label for="image" class="form-label">Gambar (Opsional)</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                <div class="form-text">Format yang didukung: JPG, JPEG, PNG, GIF</div>
                            </div>

                            <div class="mb-3">
                                <label for="title" class="form-label">Judul Artikel</label>
                                <input type="text" class="form-control" id="title" name="title" required maxlength="255">
                            </div>
                            
                            <div class="mb-3">
                                <label for="content" class="form-label">Konten Artikel</label>
                                <textarea class="form-control" id="content" name="content" rows="10" required></textarea>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="index.php" class="btn btn-secondary me-md-2">
                                    <i class="fas fa-arrow-left me-1"></i>Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Simpan Artikel
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