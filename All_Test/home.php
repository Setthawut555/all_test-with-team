<?php
    require_once('config.php');
    session_start();
    $sql = "SELECT fullname , class ,  no FROM users WHERE strid = '".$_SESSION['strid']."'";
    $result = $mysqli->query($sql);
    
    if ($result->num_rows > 0) {
      // output data of each row
      $row = mysqli_fetch_array($result);
      session_start();
      $_SESSION['fullname'] = $row['fullname'];
      $_SESSION['class'] = $row['class'];
      $_SESSION['no'] = $row['no'];
      
    } else {
      echo "0 results";
    }    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home02</title>
    <link rel="stylesheet" href="styles copy.css"/>
</head>
<body>
    <div class="container">
        <div class="nav-wrapper">
            <div class="left-size">
                <div class="var-link-wrapper" >
                    <a href="index.html">Home</a>
                </div>
                <div class="var-link-wrapper">
                    <a href="about.html">About</a>
                </div>
            </div>
            <div class="right-size">
                <div class="brand">
                    <div class = "Name">Name:</div>
                </div>
                <div class="brand">
                    <div>
                        <?php session_start(); echo $_SESSION['fullname']; ?> 
                    </div>
                </div>

                <div class="brand">
                    <div class = "Name">Class:</div>
                </div>
                <div class="brand">
                    <div>
                        <?php session_start(); echo $_SESSION['class']; ?> 
                    </div>
                </div>

                <div class="brand">
                    <div class = "Name">No:</div>
                </div>
                <div class="brand">
                    <div>
                        <?php session_start(); echo $_SESSION['no']; ?> 
                    </div>
                </div>

                <div class="brand">
                    <div class = "Name">ID:</div>
                </div>
                <div class="brand">
                    <div>
                        <?php session_start(); echo $_SESSION['strid']; ?> 
                    </div>
                </div>
                <div class="logout">
                    <div>
                        <a href="logout.php">logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg">
        
    </div>
</body>
</html>



