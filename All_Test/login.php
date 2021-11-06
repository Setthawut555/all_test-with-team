<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: home.php");
    exit;
}
 
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$strid = $fullname = $class = $no = $password = '';
$strid_err = $fullname_err = $class_err = $no_err = $password_err = '';
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }

    if(empty(trim($_POST["strid"]))){
        $strid_err = "Please enter your strid.";
    } else{
        $strid = trim($_POST["strid"]);
    }
    // Validate credentials
    

    if(empty($strid_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, strid , password FROM users WHERE strid   = ? ";
        
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_strid);
            
            // Set parameters
            $param_strid = $strid;        
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Store result
                $stmt->store_result();
                
                // Check if strid exists, if yes then verify password
                if($stmt->num_rows == 1){                    
                    // Bind result variables
                    $stmt->bind_result($id, $strid , $hashed_password);
                    if($stmt->fetch()){
                        if(password_verify($password, $hashed_password )){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["strid"] = $strid;     
                            $_SESSION["fullname"] = $fullname;             
                            
                            // Redirect user to welcome page
                            header("location: home.php");
                        } else{
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid fullname or password.";
                        }
                    }
                } else{
                    // fullname doesn't exist, display a generic error message
                    $login_err = "Invalid fullname or password.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }
    // Close connection
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>

        <?php 
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }        
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Student ID.</label>
                <input type="text" name="strid" class="form-control <?php echo (!empty($strid_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $strid; ?>">
                <span class="invalid-feedback"><?php echo $strid_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Don't have an account? <a href="sign_up.php">Sign up now</a>.</p>
        </form>
    </div>
</body>
</html>
