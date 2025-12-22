<?php
session_start();
include '../db_connect.php';

$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="../assets/images/GKJW.svg" type="image/svg+xml">
    <title>Login - Dashboard GKJW</title>
    <!-- We're using the project's own assets if possible, or CDNs for now since Gentelella assets might be complex to link relative. 
         Gentelella usually requires Bootstrap 4/5. Let's use basic Bootstrap 5 CDN to ensure it looks okay regardless of missing local assets. -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
      .login-bg {
        background: linear-gradient(135deg, #1A1A1A 0%, #2b2b2b 100%);
        min-height: 100vh;
      }
      .card {
          background-color: #242424;
          color: white;
          border: 1px solid #333;
      }
      .form-control, .input-group-text {
          background-color: #333;
          border-color: #444;
          color: white;
      }
      .form-control:focus {
          background-color: #333;
          color: white;
          border-color: #94C766;
          box-shadow: 0 0 0 0.25rem rgba(148, 199, 102, 0.25);
      }
      .btn-primary {
          background-color: #94C766;
          border-color: #94C766;
          color: white;
      }
      .btn-primary:hover {
          background-color: #7ab34d;
          border-color: #7ab34d;
      }
      .text-muted {
          color: #aaa !important;
      }
    </style>
  </head>

  <body class="login-bg">
    <div class="container d-flex align-items-center justify-content-center min-vh-100">
      <div class="row justify-content-center w-100">
        <div class="col-md-6 col-lg-4">
          
          <div class="card shadow-lg">
            <div class="card-body p-5">
              <div class="text-center mb-4">
                <h3 class="fw-bold mb-2">Admin Panel</h3>
                <p class="text-muted">GKJW Sidoarjo</p>
              </div>

              <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
              <?php endif; ?>

              <form method="POST" action="">
                <div class="mb-3">
                  <label class="form-label text-muted">Username</label>
                  <div class="input-group">
                    <span class="input-group-text">
                      <i class="fas fa-user"></i>
                    </span>
                    <input type="text" name="username" class="form-control" placeholder="Enter username" required>
                  </div>
                </div>

                <div class="mb-3">
                  <label class="form-label text-muted">Password</label>
                  <div class="input-group">
                    <span class="input-group-text">
                      <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                  </div>
                </div>

                <div class="d-grid mt-4">
                  <button type="submit" class="btn btn-primary btn-lg">
                    Sign In
                  </button>
                </div>
              </form>

              <div class="text-center mt-3">
                  <a href="../index.php" class="text-decoration-none text-muted"><small>← Back to Website</small></a>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </body>
</html>
