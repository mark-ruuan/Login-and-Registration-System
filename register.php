<?php
    require_once "config.php";
    $username = $password = $confirm_password = "";
    $username_err = $password_err = $confirm_password_err = "";

    if($_SERVER['REQUEST_METHOD'] == "POST"){
      //check if username is empty
      if(empty(trim($_POST["username"]))){
        $username_err = "Username cannot be blank";
      }
      else{
        $sql = "SELECT id FROM users where username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if($stmt){
          mysqli_stmt_bind_param($stmt, "s", $param_username);
          //set the value of username
          $param_username = trim($_POST['username']);
          if(mysqli_stmt_execute($stmt)){
            mysqli_stmt_store_result($stmt);
            if(mysqli_stmt_num_rows($stmt) == 1){
              $username_err = "This username is already taken";
            }
            else{
              $username = $_POST["username"];
            }
          }
           //close connection for statement;
          mysqli_stmt_close($stmt);
        }
      }

      //check for password;
      if(empty(trim($_POST['password']))){
        $password_err = "Password cannot be blank";
      }
      elseif(strlen(trim($_POST['password'])) < 5){
        $password_err = "Password must be atleast of 5 Characters";
      }
      else{
        $password = trim($_POST['password']);
      }

      //check for confirm password field
      if(trim($_POST['password']) != trim($_POST['confirm_password'])){
        $password_err = "Passwords should match";
      }

      //if there are no errors, insert into database
      if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        $sql = "INSERT INTO users(username, password) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        if($stmt){
          mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
          $param_username = $username;
          $param_password = password_hash($password, PASSWORD_DEFAULT);

          //Try to execute the query
          if(mysqli_stmt_execute($stmt)){
            header("location: login.php");
          }
          else{
            echo "Something went wrong.. cannot redirect";
          }
        }
        mysqli_stmt_close($stmt);
      }
      mysqli_close($conn);
    }
    
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>PHP Login System</title>
  </head>
  <body>
  <!-- navbar code below -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Php Login System</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
        <a class="nav-item nav-link active" href="./register.php">Register<span class="sr-only">(current)</span></a>
        <a class="nav-item nav-link" href="./login.php">Login</a>
        <a class="nav-item nav-link" href="#">Contact Us</a>
        </div>
    </div>
    </nav>

    <div class="container mt-4">
      <h3>Please Register Here!!</h3>
      <hr>
      <form action="" method="post">
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="inputEmail4">Username</label>
            <input type="text" class="form-control" name="username" id="inputEmail4" placeholder="Enter Username">
          </div>
          <div class="form-group col-md-6">
            <label for="inputPassword4">Password</label>
            <input type="password" class="form-control" name="password" id="inputPassword4" placeholder="Password">
          </div>  
        </div>
        <div class="form-group>
            <label for="inputPassword4">Confirm Password</label>
            <input type="password" class="form-control" name="confirm_password" id="inputPassword4" placeholder="Password">
          </div>
        <div class="form-group">
          <label for="inputAddress">Address</label>
          <input type="text" class="form-control" id="inputAddress" placeholder="1234 Main St">
        </div>
        <div class="form-group">
          <label for="inputAddress2">Address 2</label>
          <input type="text" class="form-control" id="inputAddress2" placeholder="Apartment, studio, or floor">
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="inputCity">City</label>
            <input type="text" class="form-control" id="inputCity">
          </div>
          <div class="form-group col-md-4">
            <label for="inputState">State</label>
            <select id="inputState" class="form-control">
              <option selected>Choose...</option>
              <option>...</option>
            </select>
          </div>
          <div class="form-group col-md-2">
            <label for="inputZip">Zip</label>
            <input type="text" class="form-control" id="inputZip">
          </div>
        </div>
        <div class="form-group">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="gridCheck">
            <label class="form-check-label" for="gridCheck">
              Check me out
            </label>
          </div>
        </div>
        <button type="submit" class="btn btn-primary">Sign in</button>
      </form>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>