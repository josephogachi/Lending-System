<?php
session_start();
Require '../Functions/connect.php';
Require 'checksession.php';
 
if (isset($_SESSION['user'])){
$var_session=$_SESSION["user"];

$user_query = mysqli_query($conn,"select * from lender_reg where email='$var_session'");
$user_data = mysqli_fetch_assoc($user_query);
if (isset($_POST['update'])){

   
    $username=$_POST['username'];
    $email=$_POST['email'];
    $phone=$_POST['phonenumber'];
    
    
      $user_id=$user_data['id'];
      $query = "UPDATE lender_reg SET username=?, email=?, phonenumber=? WHERE id=?";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("ssii", $username, $email, $phone,$user_id);
      $stmt->execute();
      
      // Check if the update was successful
      if ($stmt->affected_rows > 0) {
        echo "Profile updated successfully!";
      } else {
        echo "Failed to update profile.";
      }
      
       $stmt->close();
      $conn->close();
      header("Location: ./details.php");
  
      exit();
    
    
    }

?>


 

 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<!-- Bootstrap JavaScript -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="agentdetails.css">

    <title>Lender Panel</title>
    <style>
    /* body{
    background: url(./images/money-person.jpg);
    background-size: cover;
} */
</style>
</head>
<body>
    
    <div class="side-menu">
        <div class="brand-name">
            <h1>LENDER</h1>
        </div>
        <ul>
        <li><a href="lender.php"><img src="./Images/dashboard.png" alt="#" >&nbsp; <span>Dashboard</span></a></li>
            <li><a href="details.php"><img src="./Images/circle-user.png" alt="#" >&nbsp; <span>Details</span> </a></li>
            <li><a href="agentdetails.php"><img src="./Images/users-alt.png" alt="#" >&nbsp;<span>Agents</span></a> </li>
            <li> <a href="profit.php"><img src="./Images/earnings.png" alt="#" > &nbsp;<span>Profit</span></a></li>
            <li><a href="reports.php"><img src="./Images/notes-medical.png" alt="#" > &nbsp;<span>Reports</span></a></li>
            <li><a href="settings.php"><img src="./Images/dashboard.png" alt="#" > &nbsp;<span>Settings</span></a></li>
        </ul>
        <button class="menu-toggle-button">&#9776;</button>

    </div>
    <div class="container">
      
        <div class="header">
            <div class="nav">
                <div class="search">
                 
                    <img src="./images/dashboard.png" alt="">
                    <h2>Profile Details</h2>
                
                    
                </div>
                
            </div>
        </div>
                  
        <div class="profile-details">
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">

            <input type="text" value="<?php echo $user_data['username'];?>" readonly><br>
            <input type="text" value="<?php echo $user_data['email'];?>" readonly><br>
            <input type="text" value="<?php echo $user_data['phonenumber'];?>" readonly><br>
            <input type="text" value="<?php echo $user_data['ID_Number'];?>" readonly><br>
            <button type="button"  data-toggle="modal" data-target="#updateModal">UPDATE PROFILE</button>

        </div>
       
 <!-- Add a Bootstrap modal for updating the profile -->
<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updateModalLabel">Update Profile</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Form fields for updating the profile information -->
          <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" class="form-control" value="<?php echo $user_data['username']; ?>" name="username">
          </div>
          <div class="form-group">
            <label for="email">Email Address:</label>
            <input type="text" id="email" class="form-control" value="<?php echo $user_data['email']; ?>" name="email">
          </div>
          
          <div class="form-group">
            <label for="phone_number">Phone No:</label>
            <input type="text" id="phone_number" class="form-control" value="<?php echo $user_data['phonenumber']; ?>" name="phonenumber">
          </div>
          
          <button type="submit" class="btn btn-primary" name="update">Update</button>
        </form>
      </div>
    </div>
  </div>
</div>
</div>

<script src="toggle.js"></script>

</body>
</html>
<?php
    }
    else {
        echo "<script>
                location.replace('login.php');
            </script>";
    }
 
 ?>