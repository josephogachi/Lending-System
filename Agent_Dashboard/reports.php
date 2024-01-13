
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
if (isset($_POST['return'])){
  $unique_code=$_POST['unique_code'];
  $lender_id=$_POST['lender_id'];
  $agent_acc_no=$_POST['agent_account_number'];
  $amount_sent = $_POST['total_amount'];
  $commision = $_POST['expected_commision'];
  // $ID = $_POST['customer_id'];

  $statement= $conn->prepare("INSERT into agent_returns (unique_code,lender_id,agent_account_number,total_amount,expected_commision) VALUES (?,?,?,?,?)");
  $statement->bind_param("siidd",$unique_code,$lender_id,$agent_acc_no,$amount_sent,$commision);
  $statement->execute();
  $statement->close();
  header("Location: ./lendermoney.php");
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
    <style>
        .generate-report button{
    background-color: #0199fe;
    margin-top: 2%;
    color: white;
    height: 6vh;
    width: 15vw;
    border: 1px solid #0199fe;
    margin-left: 5%;
}
.generate-report button:hover{
    background-color: blue;
}
#table2 button{
    background-color: #0199fe;
    margin-top: 2%;
    color: white;
    height: 6vh;
    width: 15vw;
    border: 1px solid #0199fe;
    margin-left: 23%;
}
#table1 button{
    background-color: #0199fe;
    margin-top: 2%;
    color: white;
    height: 6vh;
    width: 15vw;
    border: 1px solid #0199fe;
    margin-left: 23%;
}
    </style>
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
                <h2>Reports</h2>
                <span>Hi <?php echo $user_data['username'];?></span>
            </div>
        </div>
    </div>
    
         
        
    </div> 
     <br><br><br><br><br><br>
     <div class="dashboard" style="margin-left:21%;margin-top:0; margin-bottom:2%; font-weight:bold;";>
        <a href="agent.php">Dashboard</a>
     </div>
 
     <div class="generate-report" style="margin-left:20%;";>
            <button type="button" class="btn-show-table1">Money Returned</button>
            <button type="button" class="btn-show-table2">Money Sent</button>
        </div>

        <div id="table1" style="display: none;">
        
        <button type="submit" name="submit" ><a href="./moneyreturnedreport.php" style="padding-top: 30px;">Generate Report</a></button>
     <div class="table" style="margin-top:3%;">
        
        <table style="margin-left:20%;";>
            
        <thead class="head">
            <tr>
                <td>ID</td>
                <td>Lent Amount</td>
                <td>Unique Code</td>
                <!-- <th>Time Allocated</th> -->
                <td>Profit Returned</td>
            </tr>
        </thead>
        <?php
        $id_count = 1; 
        $state = $conn->prepare("SELECT * FROM agent_returns WHERE agent_account_number='$acc_no'");
        $state->execute();
        $res = $state->get_result();

        while ($rows = $res->fetch_assoc()) {
            ?>
            <tr>
                <td><?php echo $id_count; ?></td>
                <td><?php echo $rows['total_amount']; ?></td>
                <td><?php echo $rows['unique_code']; ?></td>
                <td><?php echo $rows['expected_commision']; ?></td>
            </tr>
            <?php
            $id_count++; 
        }
        ?>
        </table>
        </div>
        </div>

        <div id="table2" style="display: none;">
       
        <button type="submit" name="submit" > <a href="./report.php" style="padding-top: 30px;">Generate Report </a></button>
        <div class="table" style="margin-top:3%;">
   
        <table>
        <thead class="head">
            <tr>
                <td>ID</td>
                <td>Lent Amount</td>
                <td>Unique Code</td>
                <td>Time Allocated</th>
                <td>Lender ID</td>
            </tr>
        </thead>
        <?php
        $id_count = 1; 
        $state = $conn->prepare("SELECT * FROM lender_transactions WHERE agent_account_number='$acc_no'");
        $state->execute();
        $res = $state->get_result();

        while ($rows = $res->fetch_assoc()) {
            ?>
            <tr>
                <td><?php echo $id_count; ?></td>
                <td><?php echo $rows['lent_amount']; ?></td>
                <td><?php echo $rows['unique_code']; ?></td>
                <td><?php echo $rows['time_allocated']; ?></td>
                <td><?php echo $rows['lender_id']; ?></td>
            </tr>
            <?php
            $id_count++; 
        }
        ?>
        </table>
        </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.btn-show-table1').click(function() {
                $('#table1').toggle();
                $('#table2').hide();
            });

            $('.btn-show-table2').click(function() {
                $('#table2').toggle();
                $('#table1').hide();
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