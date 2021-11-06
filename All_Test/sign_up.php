<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$strid = $fullname = $class = $no = $password = '';
$strid_err = $fullname_err = $class_err = $no_err = $password_err = '';
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate fullname
    if(empty(trim($_POST["fullname"]))){
        $fullname_err = "Please enter a fullname.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE fullname = ?";
        
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_fullname);
            
            // Set parameters
            $param_fullname = trim($_POST["fullname"]);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // store result
                $stmt->store_result();
                
                if($stmt->num_rows == 1){
                    $fullname_err = "This fullname is already taken.";
                } else{
                    $fullname = trim($_POST["fullname"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    //strid
    if(empty(trim($_POST["strid"]))){
        $strid_err = "Please enter a student ID.";     
    }
    else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE strid = ?";
        
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_strid);
            
            // Set parameters
            $param_strid = trim($_POST["strid"]);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // store result
                $stmt->store_result();
                
                if($stmt->num_rows == 1){
                    $strid_err = "This Student ID is already taken.";
                } else{
                    $strid = trim($_POST["strid"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }
    //class
    if(empty(trim($_POST["class"]))){
        $class_err = "Please enter a student Class.";     
    }
    else{
        $class = trim($_POST["class"]);
    }
    //no
    if(empty(trim($_POST["no"]))){
        $no_err = "Please enter a student No.";     
    }
    else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE no = ?";
        
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_no);
            
            // Set parameters
            $param_no = trim($_POST["no"]);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // store result
                $stmt->store_result();
                
                if($stmt->num_rows == 1){
                    $no_err = "This no is already taken.";
                } else{
                    $no = trim($_POST["no"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }
    
    // Check input errors before inserting in database
    if(empty($fullname_err) && empty($password_err)&& empty($strid_err)&& empty($class_err)&& empty($no_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (strid,fullname,class,no,password) VALUES (?, ?, ?, ?, ?)";
         
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sssss",$param_strid,$param_fullname,$param_class,$param_no,$param_password);
            
            // Set parameters
            $param_fullname = $fullname;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_strid = $strid;
            $param_class = $class;
            $param_no = $no;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to login page
                header("location: login.php");
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
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>ลงทะเบียนบัญชี</h2>
        <p>กรอกข้อมูลเพื่อสร้างบัญชี</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>รหัสนักเรียน</label>
                <input type="int" name="strid" class="form-control <?php echo (!empty($strid_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $strid; ?>">
                <span class="invalid-feedback"><?php echo $strid_err; ?></span>
            </div>  

            <div class="form-group">
                <label>ชื่อ-สกุล</label>
                <input type="text" name="fullname" class="form-control <?php echo (!empty($fullname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $fullname; ?>">
                <span class="invalid-feedback"><?php echo $fullname_err; ?></span>
            </div>    
    
            <div class="form-group"> 
                <label>ชั้น</label>
                <input type="text" name="class" class="form-control  <?php echo (!empty($class_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $class; ?>">
                <span class="invalid-feedback"><?php echo $class_err; ?></span>
            </div>    

            <div class="form-group">
                <label>เลขที่</label>
                <input type="int" name="no" class="form-control <?php echo (!empty($no_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $no; ?>">
                <span class="invalid-feedback"><?php echo $no_err; ?></span>
            </div>  

            <div class="form-group">
                <label>รหัสผ่าน</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>  

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="ลงทะเบียน">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>    
</body>
</html>