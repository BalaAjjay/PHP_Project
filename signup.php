<?php
// Include config file
require_once 'config.php';
 
// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Validate password
    if(empty(trim($_POST['password']))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST['password'])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST['password']);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = 'Please confirm password.';     
    } else{
        $confirm_password = trim($_POST['confirm_password']);
        if($password != $confirm_password){
            $confirm_password_err = 'Password did not match.';
        }
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Something went wrong. Please try again later.";
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
            height: 1000px;
           }
           .body1 {
   
    margin-top: 0px;
}
.wrapper {
                width: 100%;
                overflow: hidden;
    </style>
</head>
<body id="page1">
<!-- START PAGE SOURCE -->
<div class="extra">
  <div class="main">
    <header>
      <div class="wrapper">
      <br>
          <br>
      <!-- <h2>Book a hotel</h2> -->

       <!--  <h1><a href="index.html" id="logo">Around the World</a></h1> -->
        <div class="right">
          <!-- <div class="wrapper">
          

            <form id="search" action="#" method="post">
              <div class="bg">
                <input type="submit" class="submit" value="">
                <input type="text" class="input">
              </div>
            </form>
          </div> -->
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
      
      
      <article class="col1 pad_left1" id="main">
       <h4 style="font-size:20px">Register Here</h4>
      
      

     <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username:<sup>*</sup></label>
                <input type="text" name="username"class="form-control" placeholder="Enter Username" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
              <label>Password:<sup>*</sup></label> 
                <input type="password" name="password" class="form-control" placeholder="Enter Password" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
               <label>Confirm Password:<sup>*</sup></label> 
               <input type="password" name="confirm_password" class="form-control" placeholder="Enter conform Password" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Register">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
           <div style="font-color:green">Don have an account? <a href="login.php">Login</a>.</div>

           <br>
           <br>
           <br>
           <br>
           <br>
           <br>
             
        </form>

        
       </article>

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