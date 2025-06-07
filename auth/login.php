<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Ruangku. | Login</title>
  <link rel="stylesheet" href="../css/styles.min.css" />
  <link rel="stylesheet" href="../css/style.css" />
</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div
      class="position-relative overflow-hidden text-bg-light min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-3">
            <div class="card mb-0">
              <div class="card-body">
                <a href="../index.php" class="text-nowrap logo-img text-center d-block py-3 w-100">
                  <p style="font-size: 24px; font-weight: bold;">Ruangku</p>
                </a>
                <?php if (isset($_GET['error'])): ?>
                  <div class="alert alert-danger text-center">
                    <?= htmlspecialchars($_GET['error']) ?>
                  </div>
                <?php endif; ?>
                <form action="process_login.php" method="POST">
                  <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp">
                  </div>
                  <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password">
                  </div>
                  <input type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2" value="Sign In">
                  <div class="d-flex align-items-center justify-content-center">
                    <p class="fs-4 mb-0 fw-bold">Belum punya akun?</p>
                    <a class="text-primary fw-bold ms-2" href="./register.php">Buat akun</a>
                  </div>
                  <div style="text-align: center; margin-top: 1rem;">
                      <button type="button" onclick="goBack()" style="background: none; border: none; color: #007BFF; cursor: pointer;">
                          ← Kembali ke halaman sebelumnya
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
  <script src="../libs/jquery/dist/jquery.min.js"></script>
  <script src="../libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <!-- solar icons -->
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
  <script>
    function goBack() {
        if (document.referrer !== "") {
            window.location.href = document.referrer;
        } else {
            window.history.back();
        }
    }
  </script>
</body>
</html>