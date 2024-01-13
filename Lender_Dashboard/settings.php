<?php
session_start();
Require '../Functions/connect.php';
Require 'checksession.php';
 
if (isset($_SESSION['user'])){
$var_session=$_SESSION["user"];

$user_query = mysqli_query($conn,"select * from lender_reg where email='$var_session'");
$user_data = mysqli_fetch_assoc($user_query);
 
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
    <link id="theme-style" rel="stylesheet" href="default.css">
<style>
    body{
    background: url(./images/money-person.jpg);
    background-size: cover;
}
</style>
 

<!-- Rest of your HTML code -->

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
                  
        <div class="settings">
    <h2>Settings</h2>
    <hr>

    <h4>Theme</h4>
    <div class="theme-options">
        <label>
            <input type="radio" name="theme" value="default" checked>
            Default
        </label>
        <label>
            <input type="radio" name="theme" value="dark">
            Dark
        </label>
    </div>

    <h4>Font</h4>
    <div class="font-options">
        <label>
            <input type="radio" name="font" value="Arial" checked>
            Arial
        </label>
        <label>
            <input type="radio" name="font" value="Verdana">
            Verdana
        </label>
    </div>

    <h4>Language</h4>
    <div class="language-options">
        <select name="language">
            <option value="en">English</option>
            <option value="fr">French</option>
            <option value="de">German</option>
            <!-- Add more language options as needed -->
        </select>
    </div>

    <h4>Notifications</h4>
    <div class="notification-options">
        <label>
            <input type="checkbox" name="emailNotifications" checked>
            Email Notifications
        </label>
        <label>
            <input type="checkbox" name="pushNotifications">
            Push Notifications
        </label>
        <label>
            <input type="checkbox" name="inAppNotifications">
            In-App Notifications
        </label>
    </div>

    <h4>Privacy Settings</h4>
    <div class="privacy-options">
        <label>
            <input type="checkbox" name="profileVisibility" checked>
            Profile Visibility
        </label>
        <label>
            <input type="checkbox" name="featureAccess">
            Restrict Feature Access
        </label>
    </div>

    <h4>Account Security</h4>
    <div class="security-options">
        <a href="change-password.php">Change Password</a>
        <label>
            <input type="checkbox" name="twoFactorAuth">
            Enable Two-Factor Authentication
        </label>
    </div>

    <h4>Email Preferences</h4>
    <div class="email-options">
        <label>
            <input type="checkbox" name="emailSubscription" checked>
            Subscribe to Email Updates
        </label>
    </div>

    <h4>Data Management</h4>
    <div class="data-options">
      <button> <a href="download-data.php">Download Personal Data</a></button> 
      <button class="btn-danger" id="delete"> <a href="delete-account.php">Delete Account</a></button> 
      <button> <a href="data-sharing.php">Manage Data Sharing</a></button> 
    </div>
    <button class="btn-danger logout" id="delete"> <a href="logout.php"> LOG OUT </a></button>
</div>
<script src="toggle.js"></script>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    $(document).ready(function() {
        // Theme options change event
        $('input[name="theme"]').change(function() {
            var theme = $(this).val();
            $('#theme-style').attr('href', theme + '.css');
        });

        // Font options change event
        $('input[name="font"]').change(function() {
            var font = $(this).val();
            $('body').css('font-family', font);
        });
    });
</script>


 
       
 


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