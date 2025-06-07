<?php
    include '../includes/check_credential.php';
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard | Artikel</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href=".././css/styles.min.css" />
</head>

<body>
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
            <li class="sidebar-item">
                <a class="sidebar-link justify-content-between"  
                  href="#">
                  <div class="d-flex align-items-center gap-3">
                    <div class="round-16 d-flex align-items-center justify-content-center">
                      <i class="ti ti-logout"></i>
                    </div>
                    <a class="hide-menu" href="../auth/logout.php">Logout</a>
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
                <h2>Daftar Artikel</h2>
                <a href="create.php" class="btn btn-primary">
                  <i class="ti ti-plus"></i> Tambah Artikel Baru
                </a>
              </div>
              <table id="articlesTable" class="table table-bordered table-striped">
                <thead>
                  <tr>
                      <th>ID</th>
                      <th>Judul</th>
                      <th>Tanggal Dibuat</th>
                      <th>Tanggal Update</th>
                      <th width="200">Aksi</th>
                  </tr>
                </thead>
                  <tbody>
                  </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

  <script>
    $(document).ready(function() {
      $('#articlesTable').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
          url: '../includes/get_articles.php',
          type: 'GET',
          dataSrc: 'data'
        },
        columns: [{
            data: null,
            title: "No",
            orderable: false,
            searchable: false,
            render: function (data, type, row, meta) {
              return meta.row + 1;
            }
          },
          { data: 1, title: "Judul" },
          { data: 2, title: "Tanggal Dibuat", },
          { data: 3, title: "Tanggal Diupdate" },
          { data: 4, title: "Aksi", orderable: false, searchable: false }
        ],
        language: {
          processing: "Memproses...",
          search: "Cari:",
          lengthMenu: "Tampilkan _MENU_ entri",
          info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
          infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
          infoFiltered: "(disaring dari _MAX_ entri keseluruhan)",
          paginate: {
            first: "Pertama",
            last: "Terakhir",
            next: "Selanjutnya",
            previous: "Sebelumnya"
          }
        }
      });
    });
  </script>

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
            $('#articlesTable').DataTable().ajax.reload();
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