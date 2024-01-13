
<?php
session_start();
Require '../Functions/connect.php';
Require 'checksession.php';
// check_session();
if (isset($_SESSION['user'])){
    
$var_session=$_SESSION["user"];

$user_query = mysqli_query($conn,"select * from agent_reg where email='$var_session'");
$user_data = mysqli_fetch_assoc($user_query);
$id=$user_data['id'];
$query = "SELECT lent_amount FROM lender_transactions WHERE agent_account_number=?";
$statement = $conn->prepare($query);
$acc_no=$user_data['account_number'];
$statement->bind_param("s", $user_data["account_number"]);
$statement->execute();
$result = $statement->get_result();
 
$updated_commision_balance = mysqli_query($conn, "SELECT * FROM agent_commision WHERE agent_account_number='$acc_no'");
$total_commision = 0;
while ($row = mysqli_fetch_assoc($updated_commision_balance)) {
    $total_commision += $row['commision'];
}
if (isset($_POST['update'])){

   
    $username=$_POST['username'];
    $email=$_POST['email'];
    $phone=$_POST['phonenumber'];
    
    
      $user_id=$user_data['id'];
      $query = "UPDATE agent_reg SET username=?, email=?, phonenumber=? WHERE id=?";
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<!-- Bootstrap JavaScript -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="agent.css">

    <title>Agents Dasboard</title>
</head>
<body>

    <div class="sidemenu">
        <div class="title">
            <h1>Agent</h1></div>
        <ul class="menu">
            <li class="active">
                <a href="details.php">
                    <img src="assets/user.png" alt="#">
                    <span>Details</span>
                </a>

            </li>
            
            <li>
                <a href="lender.php">
                <img src="../Customer_Dashboard/assets/agents.png" alt="#" width="40px">
                    <span>Lender</span>
                </a>

            </li>
        
            <li>
                <a href="customer.php">
                    <img src="assets/users-alt.png" alt="#">
                    <span>Customer</span>
                </a>

            </li>
            
            <li>
                <a href="commision.php">
                    <img src="assets/sack-dollar.png" alt="#">
                    <span>Commission</span>
                </a>

            </li>
            
            <li>
                <a href="reports.php">
                    <img src="assets/earnings.png" alt="#">
                    <span>Reports</span>
                </a>

            </li>
            <li>
                <a href="settings.php">
                    <img src="assets/settings.png" alt="#" width="37px">
                    <span>Settings</span>
                </a>

            </li>
            
            <li class="logout">
                <a href="logout.php">
                    <img src="assets/dashboard.png" alt="#">
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="content">
        <div class="header-wrapper">
            <div class="header-title">
                <h2>Profile</h2>
                <span>Hi <?php echo $user_data['username'];?></span>
            </div>
        </div>
    </div>
    
         
        
    </div> 
     <br><br><br><br><br><br>
     <div class="dashboard" style="margin-left:21%;margin-top:0; margin-bottom:2%; font-weight:bold;";>
        <a href="agent.php">Dashboard</a>
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