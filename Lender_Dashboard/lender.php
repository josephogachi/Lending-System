<?php
session_start();
Require '../Functions/connect.php';
Require 'checksession.php';
// check_session();
if (isset($_SESSION['user'])){
    
$var_session=$_SESSION["user"];

$user_query = mysqli_query($conn,"select * from lender_reg where email='$var_session'");
$user_data = mysqli_fetch_assoc($user_query);
$lender_id=$user_data['id'];
$lender_transactions = mysqli_query($conn,"select * from lender_transactions where id='$lender_id'");
$lender_trans = mysqli_fetch_assoc($lender_transactions);
$updated_details = mysqli_query($conn, "SELECT * FROM lender_transactions WHERE lender_id={$user_data['id']}");
$updated_commision = mysqli_query($conn, "SELECT * FROM agent_commision WHERE lender_id={$user_data['id']}");
$sum = 0;
while ($row = mysqli_fetch_assoc($updated_details)) {
    $sum += $row['lent_amount'];
}
$comm = 0;
while ($row = mysqli_fetch_assoc($updated_commision)) {
    $comm += $row['commision'];
}
$updated_returns = mysqli_query($conn, "SELECT * FROM agent_returns WHERE lender_id={$user_data['id']}");
$returns = 0;
while ($rowss= mysqli_fetch_assoc($updated_returns)) {
    $returns += $rowss['updated_total'];
}
$lender_id = $user_data['id'];

$sql = "SELECT SUM(amount) AS balance
        FROM top_up
        WHERE lender_id = '$lender_id'";

$result = $conn->query($sql);

if ($result) {
    $row = $result->fetch_assoc();
    $balance = $row['balance'];
} else {
    echo "Error executing query: " . $conn->error;
}

$updated_balance = $balance - $sum+$returns-$comm;

$updates = "UPDATE lender_reg
            SET updated_balance = $updated_balance
            WHERE id = '$lender_id'";

if ($conn->query($updates) === TRUE) {
     
} else {
    echo "Error updating table: " . $conn->error;
}
$query = "SELECT account_number FROM agent_reg";
$result = mysqli_query($conn, $query);
function generateUniqueCode($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code = '';
    
    $charactersLength = strlen($characters);
    for ($i = 0; $i < $length; $i++) {
        $randomIndex = mt_rand(0, $charactersLength - 1);
        $code .= $characters[$randomIndex];
    }
    
    return $code;
}

if (isset($_POST['lend'])){
$uniqueCode = generateUniqueCode(8);
$agent_number=$_POST['agent_account_number'];
$account_balance = $_POST['account_balance'];
$amount_lent = $_POST['lent_amount'];
$lender_id=$_POST['lender_id'];

$accountExists = false;
while ($agent = mysqli_fetch_assoc($result)) {
    if ($agent_number === $agent['account_number']) {
        $accountExists = true;
        break;
    }
}

if (!$accountExists) {
    echo "
    <script>
    alert('Account Number does not exist');
    </script>
    ";
}
elseif ($amount_lent > $account_balance) {
    echo"
    <script>
    alert('Insufficient Balance to make this transaction');
    </script>
    ";}
else{
    $time = date('H:i:s');
$statement= $conn->prepare("INSERT into lender_transactions (agent_account_number,lent_amount,lender_id,unique_code) VALUES (?,?,?,?)");
$statement->bind_param("iiis",$agent_number,$amount_lent,$lender_id,$uniqueCode);
$statement->execute();
$statement->close();
$conn->close();
header("Location: ./lender.php");
exit();
     
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
                <img src="./images/dashboard.png" alt="">
                    <h2>Dashboard</h2><br>
                    
                </div>
     

                <!-- <div class="user">
                    <a href="#" class="btn">Add New</a>
                </div> -->
            </div>
            <h4 class="hello" style=" margin-left: 3%;
  margin-bottom:3%; padding-bottom:3%;";>Hi <?php echo $user_data['username'];?></h4>
        </div>
<!-- <div class="greeting">
    <h3>Hi Gaudencia</h3>
</div> -->
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">

        <div class="content">
            <div class="cards">
                <div class="cards">
                    <div class="box">

                    <div class="icon-class">
                        <img src="./Images/sack-dollar.png" alt="">
                    </div>
                      
                    <div class="Balance">
                        <input type="text" name="account_balance" required placeholder="<?php echo $user_data['updated_balance'];?>" value="<?php echo $user_data['updated_balance'];?>" readonly>
                        <h5> Account Balance</h5>
                        </div>
                       

                    </div>
                </div>
                <div class="cards">
                    <div class="box">

                    <div class="icon-class">
                        <img src="./Images/address-book.png" color= "blue" alt="">
                    </div>

                    <div class="Balance">
                        <input type="text"  required name="agent_account_number" placeholder="+254 7...">
                        <h5> Agent's Account No</h5>
                        </div>
                       

                    </div>
                </div>
                
                <div class="cards">
                    <div class="box">

                    <div class="icon-class">
                        <img src="./Images/sort-amount-down-alt.png" alt="">
                    </div>

                    <div class="Balance">
                        <input type="text" name="lent_amount" placeholder="Ksh. ..">
                        <input type="hidden" name="lender_id" value="<?php echo $user_data['id'];?>">

                        <h5> Amount to Lend</h5>
                        </div>
                    </div>
                    
                </div>
                <div class="cards">
                    <div class="box">
                    <div class="icon-class">
                       <button type="submit" class="btn" name="lend"> LEND  MONEY</button>
                       </form>
                        </div>
                   <br>







                   <div class="icon-class-1">
                    <button id="topUpButton" data-toggle="modal" data-target="#topUpModal" class="btn">Withdraw</button>
                    
                    </div>


                   
                    </div>
                    
                </div>

            </div>


            <div class="content-2">
                <div class="money-returned">
                    <div class="title">
                        <h2> Transactions</h2>
                        <a href="profit.php" class="btn">View All </a>
                    </div>
                                        <!-- <div > -->

                    <table id="table-container">

                        <tr>
                            <th>Agent ID</th>
                            <th>Loan</th>
                            <th>Unique Code</th>
                            <!-- <th>Amount Returned</th> -->
                            <th>Status</th>
                            <th>Options</th>
                        </tr>
                        <?php
        $id_count = 1; 
 $state = $conn->prepare("SELECT * FROM lender_transactions WHERE lender_id={$user_data['id']}");
 $state->execute();
 $res= $state->get_result();

$display_limit = 5;  
$row_count = 0;       

while ($rows = $res->fetch_assoc()) {
    $row_count++;

    if ($row_count > $display_limit) {
        break;   
    }
 
    ?>
                        <tr>
                            <td><?php echo $rows['agent_account_number']; ?></td>
                            <td><?php echo $rows['lent_amount']; ?></td>
                            <td><?php echo $rows['unique_code']; ?></td>
                            <!-- <td>23,400.00</td> -->
                            <td>Approved</td>
                            <td><a href="profit.php" class="btn">View</a></td>
                        </tr>
                      
                        <?php

             $id_count++; 
             }
             ?>
                        
                    </table>
                   

                </div>
                <!-- </div> -->
                <div class="profit">
                    <div class="title">
                        <h2> Agents Details</h2>
                        <a href="./agentdetails.php" class="btn">View All </a>
                    </div>
                    <!-- <div class="report">
                        <h6>Generate Report</h6>
                   <a href=""> <button>GENERATE  </button></a>
                        
                    </div> -->
                    <table id="agent-table">
                    <tr>
                        <th class="desktop-only">ID</th>
                        <th>Profile</th>
                        <th>Name</th>
                        <th>Account No.</th>
                    </tr>
                    <?php
        $id_count = 1; 
 $stmt = $conn->prepare("SELECT * from agent_reg");
 $stmt->execute();
 $result = $stmt->get_result();

$display_limit = 5;  
$row_count = 0;       

while ($row = $result->fetch_assoc()) {
    $row_count++;

    if ($row_count > $display_limit) {
        break;   
    }
 
    ?>
    <tr>
        <td  class="desktop-only"><?php echo $id_count; ?></td>
        <td><img src="./Images/user.png" alt=""></td>
        <td><?php echo $row['username']; ?></td>
        <td><?php echo $row['account_number']; ?></td>
    </tr>
    <?php

    $id_count++; 
}
?>

                    
                    
                    </table>
                </div>
                </div>
            </div>
       
          
    </div>
    


    <script>
    const menuToggle = document.querySelector(".menu-toggle-button");
    const sideMenu = document.querySelector(".side-menu");

    menuToggle.addEventListener("click", function () {
        sideMenu.classList.toggle("show-menu");
    });

    window.addEventListener('DOMContentLoaded', function() {
    var table = document.getElementById('table-container');
    var rows = table.querySelectorAll('tr');

    function hideColumns() {
        var screenWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
        var hideColumns = screenWidth <= 768; // Set the desired breakpoint for hiding columns

        rows.forEach(function(row) {
            var cells = row.querySelectorAll('th, td');
            cells.forEach(function(cell, index) {
                if (hideColumns && (index === 3 || index === 4)) {
                    cell.style.display = 'none';
                } else {
                    cell.style.display = '';
                }
            });
        });
    }

    hideColumns(); // Initial call to hide columns on page load

    window.addEventListener('resize', function() {
        hideColumns(); // Call hideColumns() when the window is resized
    });
});

window.addEventListener('DOMContentLoaded', function() {
    var table = document.getElementById('agent-table');
    var thElements = table.querySelectorAll('th.desktop-only');
    var tdElements = table.querySelectorAll('td.desktop-only');

    function hideColumns() {
        var screenWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
        var hideColumns = screenWidth <= 768; // Set the desired breakpoint for hiding columns

        if (hideColumns) {
            thElements.forEach(function(th) {
                th.style.display = 'none';
            });
            tdElements.forEach(function(td) {
                td.style.display = 'none';
            });
        } else {
            thElements.forEach(function(th) {
                th.style.display = '';
            });
            tdElements.forEach(function(td) {
                td.style.display = '';
            });
        }
    }

    hideColumns(); // Initial call to hide columns on page load

    window.addEventListener('resize', function() {
        hideColumns(); // Call hideColumns() when the window is resized
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