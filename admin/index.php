<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <link rel="shortcut icon" href="../assets/images/favicon.ico" type="image/x-icon">
  <link rel="icon" href="../assets/images/favicon.ico" type="image/x-icon">
  <title>Ojas Aura</title>
  <link rel="stylesheet" type="text/css" href="../assets/css/mdb.min.css" />  
  <link href="../assets/css/style.css?id=400" rel="stylesheet" type="text/css" />
  <link href="../assets/css/sidebar.css?id=300" rel="stylesheet" type="text/css" />
  <link href="../assets/css/custom.css?id=400" rel="stylesheet" type="text/css" />
  <!-- SweetAlert CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
  <div class="sign-in-fullscreen-area">
    <div class="sign_in_up_form_area">
      <div class="logo"><img src="../assets/images/logo.png" alt="logo" /></div>
      <form id="loginForm" method="POST">
        <div class="form_area">
          <div class="mb-3">
            <input type="text" id="username" class="form-control" placeholder="Username" name="user_name" maxlength="30" required>
            <div id="usernameError" class="error-message"></div> <!-- Container for username error -->
          </div>
          <div class="mb-3">
            <input type="password" id="password" class="form-control" placeholder="Password" name="password" maxlength="15" required>
            <div id="passwordError" class="error-message"></div> <!-- Container for password error -->
            <span><i class="far fa-eye-slash toggle-password" id="togglePassword"></i></span> <!-- Eye icon -->
          </div>
          <div class="form-group">
            <button type="submit" id="btn-sign-in" class="btn btn-submit btn-block">SIGN IN</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Js -->
  <script src="../assets/js/jquery-3.1.1.js"></script>
  <script src="../assets/js/mdb.min.js"></script>
  <!-- SweetAlert JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="../assets/js/login_script.js"></script>
</body>

</html>
