
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

    <title>Agents Dashboard</title>
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
                <h2>Customer Reports</h2>
                <span>Hi <?php echo $user_data['username'];?></span>
            </div>
        </div>
    </div>
    
         
        
    </div> 
     <br><br><br><br><br><br>
     <div class="dashboard" style="margin-left:21%;margin-top:0; margin-bottom:2%; font-weight:bold;";>
        <a href="agent.php">Dashboard</a>
     </div>
     <div class="table" style="margin-top:3%;">
    <h2 style="margin-left:15%; text-align:center;";>Money Assigned Reports</h2>
    <hr>
    <table>
        <thead class="head">
            <tr>
                <td>ID</td>
                <td>Money Allocated</td>
                <td>Time Allocated</td>
                <td>Customer Number</td>
                <td>Amount Returned</td>
            </tr>
        </thead>
        <tbody>
            <?php
            $id_count = 0;
            $account_no=$user_data['account_number'];
            $stmt = $conn->prepare("SELECT * from customer_money where agent_id='$id'");
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
            ?>
            <tr>
                <td><?php echo $id_count; ?></td>
                <td><?php echo $row['amount_lent']; ?></td>
                <td><?php echo $row['time_allocated']; ?></td>
                <td><?php echo $row['customer_number']; ?></td>
                <td><?php echo $row['total_amount']; ?></td>
            </tr>
            <?php $id_count = $id_count + 1 ;} ?>
        </tbody>
    </table>
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