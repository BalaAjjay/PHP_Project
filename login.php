<?php
// Include config file
require_once 'config.php';
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = 'Please enter username.';
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST['password']))){
        $password_err = 'Please enter your password.';
    } else{
        $password = trim($_POST['password']);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT username, password FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            /* Password is correct, so start a new session and
                            save the username to the session */
                            session_start();
                            $_SESSION['username'] = $username;      
                            header("location: index.php");
                        } else{
                            // Display an error message if password is not valid
                            $password_err = 'The password you entered was not valid.';
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = 'No account found with that username.';
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Book a hotel</title>
<meta charset="utf-8">
<link rel="stylesheet" href="css/reset.css" type="text/css" media="all">
<link rel="stylesheet" href="css/layout.css" type="text/css" media="all">
<link rel="stylesheet" href="css/style.css" type="text/css" media="all">
<script type="text/javascript" src="js/jquery-1.4.2.js" ></script>
<script type="text/javascript" src="js/cufon-yui.js"></script>
<script type="text/javascript" src="js/cufon-replace.js"></script>
<script type="text/javascript" src="js/Myriad_Pro_600.font.js"></script>
<!--[if lt IE 9]>
<script type="text/javascript" src="js/ie6_script_other.js"></script>
<script type="text/javascript" src="js/html5.js"></script>
<![endif]-->

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        
        #main{
           
           
            border-radius: 20px;
            margin-top: 0px;
            margin-left: 230px;       

           }
           
           .wrapper {
                width: 100%;
                overflow: hidden;
}
    </style>
</head>
<body id="page1">
<!-- START PAGE SOURCE -->
<div class="extra">
  <div class="main">
    <header>
      <div class="wrapper">
      <!-- <h1><a href="index.html" id="logo">Around the World</a></h1> -->
        <div class="right">
          <div class="wrapper">
            <!-- <form id="search" action="#" method="post">
              <div class="bg">
                <input type="submit" class="submit" value="">
                <input type="text" class="input">
              </div>
            </form> -->
            <br>
          <br>
          </div>
          <div class="wrapper">
            <nav>
              <ul id="top_nav">
                <li><a href="signup.php">Register</a></li>
                <li><a href="login.php">Log In</a></li>
                <li><a href="#">Help</a></li>
              </ul>
            </nav>
          </div>
        </div>
      </div>
      <nav>
        <ul id="menu">
          <li><a href="index.php" class="nav1">Home</a></li>
          <li><a href="#" class="nav2">About Us </a></li>
          <li><a href="#" class="nav3">Our Tours</a></li>
          <li><a href="#" class="nav4">Destinations</a></li>
          <li class="end"><a href="#" class="nav5">Contacts</a></li>
        </ul>
      </nav>
      
      
      <article class="col3 pad_left1" id="main">
      
      <h4 style="font-size:25px">Login Here</h4>
      <br>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
             <label>Username:<sup>*</sup></label> 
                <input type="text" name="username"class="form-control" placeholder="Username" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                 <label>Password:<sup>*</sup></label>
                <input type="password" name="password" placeholder="Password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Don't have an account? <a href="signup.php">Sign up now</a>.</p>
        </form>
       

      <br>
      <br>
      </article>
      
    </section>
  </div>
  <div class="block"></div>
</div>
<div class="body1">
  <div class="main">
    <footer>
      <div class="footerlink">
        <p class="lf">Copyright &copy; 2017 <a href="#">SiteName</a> - All Rights Reserved</p>
        <!-- <p class="rf">Design by <a href="http://www.templatemonster.com/">TemplateMonster</a></p> -->
        <div style="clear:both;"></div>
      </div>
    </footer>
  </div>
</div>
<script type="text/javascript"> Cufon.now(); </script>
<!-- END PAGE SOURCE -->
</body>
</html>