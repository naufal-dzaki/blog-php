<?php
include '../includes/db.php';
include '../includes/check_credential.php';

$id = $_GET['id'] ?? 0;
$article = null;

if ($id) {
    $query = "SELECT * FROM articles WHERE id = " . intval($id);
    $result = mysqli_query($koneksi, $query);
    $article = mysqli_fetch_assoc($result);
}

if (!$article) {
    echo "<script>alert('Artikel tidak ditemukan!'); window.location.href = 'index.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($article['title']); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href=".././css/styles.min.css" />
    <style>
        .article-image {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .article-content {
            line-height: 1.8;
            font-size: 1.1rem;
        }
        .article-meta {
            background: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 15px;
            margin: 20px 0;
        }
    </style>
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
                            <h2>Detail Artikel</h2>
                        </div>
                        <div class="card-body">
                            <article>
                                <header class="mb-4">
                                    <?php if ($article['image']): ?>
                                        <div class="text-center mb-4">
                                            <img src="../uploads/<?php echo htmlspecialchars($article['image']); ?>" 
                                                alt="<?php echo htmlspecialchars($article['title']); ?>" 
                                                class="article-image">
                                        </div>
                                    <?php endif; ?>
                                    <h1 class="display-5 fw-bold mb-3">
                                        <?php echo htmlspecialchars($article['title']); ?>
                                    </h1>
                                    
                                    <div class="article-meta">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar-plus me-1"></i>
                                                    <strong>Dibuat:</strong> 
                                                    <?php echo date('d F Y, H:i', strtotime($article['created_at'])); ?>
                                                </small>
                                            </div>
                                            <div class="col-md-6">
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar-edit me-1"></i>
                                                    <strong>Diupdate:</strong> 
                                                    <?php 
                                                    if ($article['updated_at']) {
                                                        echo date('d F Y, H:i', strtotime($article['updated_at']));
                                                    } else {
                                                        echo 'Belum pernah diupdate';
                                                    }
                                                    ?>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </header>
                                
                                <div class="article-content">
                                    <?php echo nl2br(htmlspecialchars($article['content'])); ?>
                                </div>
                            </article>
                            
                            <hr class="my-4">
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group" role="group">
                                    <a href="edit.php?id=<?php echo $article['id']; ?>" class="btn btn-warning">
                                        <i class="fas fa-edit me-1"></i>Edit Artikel
                                    </a>
                                    <button class="btn btn-danger" onclick="confirmDelete(<?php echo $article['id']; ?>)">
                                        <i class="fas fa-trash me-1"></i>Hapus Artikel
                                    </button>
                                </div>
                                <a href="index.php" class="btn btn-secondary">
                                    <i class="fas fa-list me-1"></i>Lihat Semua Artikel
                                </a>
                            </div>
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
  <script>
    function confirmDelete(id) {
      if (confirm("Apakah kamu yakin ingin menghapus artikel ini?")) {
        $.ajax({
          url: '../includes/delete_article.php',
          type: 'POST',
          data: { id: id },
          success: function(response) {
            alert("Artikel berhasil dihapus.");
            window.location.href = '/dashboard/index.php';
          },
          error: function(xhr) {
            alert("Gagal menghapus artikel.");
          }
        });
      }
    }
  </script>
</body>
</html>