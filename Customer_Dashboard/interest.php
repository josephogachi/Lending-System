<?php
session_start();
include '../Functions/connect.php';
include 'checksession.php';
// check_session();
if (isset($_SESSION['user'])){
    
$var_session=$_SESSION["user"];
$user_query = mysqli_query($conn,"select * from customer_reg where email='$var_session'");
$user_data = mysqli_fetch_assoc($user_query);
$phoneNumber=$user_data['phonenumber'];
$cus_ID=$user_data['id'];
$id=$user_data['id'];


$agent_transactions = mysqli_query($conn,"select * from customer_money where customer_number='$phoneNumber'");
// $agent_trans = mysqli_fetch_assoc($agent_transactions);
$sumLentAmount = 0;  
$expectedInterest=0;
$totalamount=0;


// / Iterate over the fetched rows and sum the lent_amount
while ($row = mysqli_fetch_assoc($agent_transactions)) {
    $sumLentAmount += $row['amount_lent'];
    $expectedInterest+=$row['expected_interest'];
    $totalamount+=$row['total_amount'] ;

}
$customer_transactions = mysqli_query($conn,"select * from customer_returns where customer_id='$cus_ID'");
$customer_trans = mysqli_fetch_assoc($customer_transactions);
$remInterest=0;
$remamount=0;

$updated_topup_balance = mysqli_query($conn, "SELECT * FROM customer_top_up WHERE customer_id='$cus_ID'");
$total_top_up = 0;
 while ($rows = mysqli_fetch_assoc($customer_transactions)) {
    $remInterest+=$rows['expected_interest'];
    $remainingInterest=$expectedInterest-$remInterest;
    $remamount+=$rows['amount_sent'] ;

}
while ($row = mysqli_fetch_assoc($updated_topup_balance)) {
    $total_top_up += $row['amount'];
}
$remainingTotal=$totalamount-$remamount+$total_top_up;



if (isset($_POST['send'])){
    $agent_id=$_POST['agent_id'];
    $amount_sent = $_POST['amount_sent'];
    $unique_code = $_POST['unique_code'];
    $interest = $_POST['expected_interest'];
    $ID = $_POST['customer_id'];

    $statement= $conn->prepare("INSERT into customer_returns (agent_id,amount_sent,unique_code,expected_interest,customer_id) VALUES (?,?,?,?,?)");
    $statement->bind_param("idsdi",$agent_id,$amount_sent,$unique_code,$interest,$ID);
    $statement->execute();
    $statement->close();
    header("Location: ./customer.php");
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

    <link rel="stylesheet" href="../Agent_Dashboard/agent.css">

    <title>Customer Dasboard</title>
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
                <h2>Dashboard</h2>
                <span>Hi <?php echo $user_data['username'];?></span>
            </div>
        </div>
    </div>
    
         
    </div> 
     <br><br><br><br><br><br>
     <div class="dashboard" style="margin-left:21%;margin-top:0; margin-bottom:2%; font-weight:bold;";>
        <a href="customer.php">Dashboard</a>
     </div>
     <div class="table" style="margin-top:3%;">
    <h2 style="margin-left:15%; text-align:center;";>Money Returned Reports</h2>
    <hr>
    <table>
        <thead class="head">
            <tr>
                <td>ID</td>
                <td>Unique Code</td>
                <td>Amount Sent</td>
                <td>Interest</td>
                <td>Agent ID</td>
            </tr>
        </thead>
        <tbody>
            <?php
            $id_count = 0;
            // $account_no=$user_data['account_number'];
            $stmt = $conn->prepare("SELECT * from customer_returns where customer_id='$id'");
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
            ?>
            <tr>
                <td><?php echo $id_count; ?></td>
                <td><?php echo $row['unique_code']; ?></td>
                <td><?php echo $row['amount_sent']; ?></td>
                <td><?php echo $row['expected_interest']; ?></td>
                <td><?php echo $row['agent_id']; ?></td>
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