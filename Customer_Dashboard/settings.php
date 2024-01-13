
<?php
session_start();
Require '../Functions/connect.php';
Require 'checksession.php';
// check_session();
if (isset($_SESSION['user'])){
    
    $var_session=$_SESSION["user"];

    $user_query = mysqli_query($conn,"select * from customer_reg where email='$var_session'");
    $user_data = mysqli_fetch_assoc($user_query);
    $phoneNumber=$user_data['phonenumber'];
    $cus_ID=$user_data['id'];

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
<link id="theme-style" rel="stylesheet" href="../Lender_Dashboard/default.css">
    
    <link rel="stylesheet" href="../Agent_Dashboard/agent.css">
    
    <title>Customer Dasboard</title>
    <style>
    body{
    background: url(../Lender_Dashboard/images/money-person.jpg);
    background-size: cover;
}
.data-options button{
color:white;
}
table button{
    height: 6vh;
    width: 10vw;
    border: 1px solid #0199fe;
    background-color: #0199fe;
    color: white;
}
.data-options a{
    /* color: #0199fe; */
    text-decoration: none;
}
.data-options button{
    height: 6vh;
    width: 14vw;
    border: 1px solid #0199fe;
    background-color: #0199fe;
    color: white;
}
#delete{
    background-color: red;
    border: 1px solid red;
}
.logout{
    height: 6vh;
    width: 14vw;
    border: 1px solid #0199fe;
    background-color: #0199fe;
    color: white;
}
</style>
</head>
<body>

    <div class="sidemenu">
        <div class="title">
            <h1>Customer</h1></div>
            <ul class="menu">
            <li class="active">
                <a href="details.php">
                    <img src="assets/user.png" alt="#">
                    <span>Details</span>
                </a>

            </li>
            
        
            <li>
                <a href="agent.php">
                    <img src="assets/users-alt.png" alt="#">
                    <span>Agent</span>
                </a>

            </li>
            
            <li>
                <a href="interest.php">
                    <img src="assets/sack-dollar.png" alt="#">
                    <span>Interest</span>
                </a>

            </li>
            
           
            <li>
                <a href="settings.php">
                    <img src="../Agent_Dashboard/assets/settings.png" alt="#" width="37px">
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
                <h2>Settings</h2>
                <span>Hi <?php echo $user_data['username'];?></span>
            </div>
        </div>
    </div>
    
         
        
    </div> 
     <br><br><br><br><br><br>
     <div class="dashboard" style="margin-left:21%;margin-top:0; margin-bottom:2%; font-weight:bold;";>
        <a href="customer.php">Dashboard</a>
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