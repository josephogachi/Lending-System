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
</head>
<body>
    <div class="side-menu">
        <div class="brand-name">
            <h1>LENDER</h1>
        </div>
        <ul>
            <li><a href="lender.php"><img src="./Images/dashboard.png" alt="#">&nbsp;<span>Dashboard</span></a></li>
            <li><a href="details.php"><img src="./Images/circle-user.png" alt="#">&nbsp;<span>Details</span></a></li>
            <li><a href="agentdetails.php"><img src="./Images/users-alt.png" alt="#">&nbsp;<span>Agents</span></a></li>
            <li><a href="profit.php"><img src="./Images/earnings.png" alt="#">&nbsp;<span>Profit</span></a></li>
            <li><a href="reports.php"><img src="./Images/notes-medical.png" alt="#">&nbsp;<span>Reports</span></a></li>
            <li><a href="settings.php"><img src="./Images/dashboard.png" alt="#">&nbsp;<span>Settings</span></a></li>
        </ul>
        <button class="menu-toggle-button">&#9776;</button>

    </div>
    <div class="container">
        <div class="header">
            <div class="nav">
                <div class="search">
                    <img src="./images/dashboard.png" alt="">
                    <h2>Reports</h2>
                </div>
            </div>
        </div>

        <div class="generate-report">
            <button type="button" class="btn-show-table1">Money Returned</button>
            <button type="button" class="btn-show-table2">Money Sent</button>
        </div>

        <div id="table1" style="display: none;">
        
        <button type="submit" name="submit" ><a href="./moneyreturnedreport.php" style="padding-top: 30px;">Generate Report</a></button>
        <table>
            
        <thead>
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
        $state = $conn->prepare("SELECT * FROM agent_returns WHERE lender_id={$user_data['id']}");
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

        <div id="table2" style="display: none;">
       
        <button type="submit" name="submit" > <a href="./report.php" style="padding-top: 30px;">Generate Report </a></button>
   
        <table>
        <thead>
            <tr>
                <td>ID</td>
                <td>Lent Amount</td>
                <td>Unique Code</td>
                <td>Time Allocated</th>
                <td>Agent Account Number</td>
            </tr>
        </thead>
        <?php
        $id_count = 1; 
        $state = $conn->prepare("SELECT * FROM lender_transactions WHERE lender_id={$user_data['id']}");
        $state->execute();
        $res = $state->get_result();

        while ($rows = $res->fetch_assoc()) {
            ?>
            <tr>
                <td><?php echo $id_count; ?></td>
                <td><?php echo $rows['lent_amount']; ?></td>
                <td><?php echo $rows['unique_code']; ?></td>
                <td><?php echo $rows['time_allocated']; ?></td>
                <td><?php echo $rows['agent_account_number']; ?></td>
            </tr>
            <?php
            $id_count++; 
        }
        ?>
        </table>
        </div>
    </div>
<script src="toggle.js"></script>
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
