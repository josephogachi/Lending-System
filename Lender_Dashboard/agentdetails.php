<?php
session_start();
Require '../Functions/connect.php';
Require 'checksession.php';
 
if (isset($_SESSION['user'])){
    
    if (isset($_POST['search'])) {
        $searchTerm = $_POST['search'];
    
        $query = "SELECT * FROM agent_reg WHERE username LIKE '$searchTerm%'";
    
        $result = mysqli_query($conn, $query);
    
        if (mysqli_num_rows($result) > 0) {
            // User(s) found
            $row = mysqli_fetch_assoc($result);
            // ... process the found user(s) data
        } else {
             
            echo " 
            <script>
            alert('User not found');
            </script>
            ";
        }
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
                    <!-- <input type="text" placeholder="Search.."> -->
                    <!-- <button type="submit"><img src="./Images/search.png"alt=""></button> -->
                    <img src="./images/dashboard.png" alt="">
                    <h2>Agent Details</h2>
                    <!-- <h3>Hi Gaudencia</h3> -->
                    
                </div>
                
            </div>
        </div>
                  
  <div class="agents-body">
    <div class="agent-search">
         
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="mb-4">
                <input type="text" name="search" id="search" placeholder="Search Agent" value="<?php echo isset($searchTerm) ? $searchTerm : ''; ?>" >
                <button type="submit">Search</button>
            
            
        </form>
        
    </div>
    <?php if (isset($row)): ?>
            <form class="agent-display">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text"  id="username" value="<?php echo $row['username']; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="email">ID Number:</label>
                    <input type="email"   id="email" value="<?php echo $row['ID_Number']; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="age">Account Number:</label>
                    <input type="number"   id="age" value="<?php echo $row['account_number']; ?>" readonly>
                </div>
            </form>
        <?php endif; ?>
    <div class="agent-table">
    <table>
        <thead>
            <td>ID</td>
            <td>Agent Name</td>
            <td>Agent Account Number</td>
            <td>Agent ID Number</td>
        </thead>
        <?php
        $id_count = 1; 
 $state = $conn->prepare("SELECT * FROM agent_reg");
 $state->execute();
 $res= $state->get_result();

    

while ($rows = $res->fetch_assoc()) {
    ?>
        <tr>
            <td><?php echo $id_count; ?></td>
            <td><?php echo $rows['username']; ?></td>
            <td><?php echo $rows['account_number']; ?></td>
            <td><?php echo $rows['ID_Number']; ?></td>

        </tr>
        <?php

$id_count++; 
}
?>
    </table>
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