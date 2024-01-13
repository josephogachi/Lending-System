<?php
session_start();
Require '../Functions/connect.php';
Require 'checksession.php';
 
if (isset($_SESSION['user'])){
$var_session=$_SESSION["user"];


$user_query = mysqli_query($conn,"select * from lender_reg where email='$var_session'");
$user_data = mysqli_fetch_assoc($user_query);
$lender_id=$user_data['id'];

 


if (isset($_POST['commision_send'])){
  $lender_id=$_POST['lender_id'];
  $agent_acc_no = $_POST['agent_account_number'];
  $unique_code = $_POST['unique_code'];
  $commision = $_POST['commision'];
  // $ID = $_POST['customer_id'];
  echo $commision;
echo $agent_acc_no;
echo $unique_code;
  $statement= $conn->prepare("INSERT into agent_commision (agent_account_number,unique_code,commision,lender_id) VALUES (?,?,?,?)");
  $statement->bind_param("isdi",$agent_acc_no,$unique_code,$commision,$lender_id);
  $statement->execute();
  $statement->close();
  header("Location: ./profit.php");
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
                    <h2>Profit</h2>
                
                </div>
                
            </div>
        </div>
     <div class="send-commision">
     <!-- <button id="commissionButton" class="bottom" data-toggle="modal" data-target="#commissionModal"
     >Send Commision</button> -->
     <button id="topUpButton" data-toggle="modal" data-target="#topUpModal" class="btn" style="margin-left:2%; border-radius:0; margin: top 10px;;";>Top Up</button>

     </div>
     <div class="icon-class-1" >
                    
                    </div>

        <table>
        <thead>
            <td class="hide-on-small">ID</td>
            <td>Lent Amount</td>
            <td>Unique Code</td>
            <td class="hide-on-small">Time Allocated</td>
            <td>Profit Returned</td>
        </thead>
        <?php
        $id_count = 1; 
 $state = $conn->prepare("SELECT * FROM lender_transactions WHERE lender_id={$user_data['id']}");
 $state->execute();
 $res= $state->get_result();

    

while ($rows = $res->fetch_assoc()) {
    ?>
        <tr>
            <td class="hide-on-small"><?php echo $id_count; ?></td>
            <td><?php echo $rows['lent_amount']; ?></td>
            <td><?php echo $rows['unique_code']; ?></td>
            <td class="hide-on-small"><?php echo $rows['time_allocated']; ?></td>
           <td> <button class="view-button btn btn-primary" data-toggle="modal" data-target="#detailsModal" data-unique-code="<?php echo $rows['unique_code']; ?>">View</button></td>

        </tr>
        <?php

$id_count++; 
}
?>

<!-- Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailsModalLabel">Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Modal content will be loaded here dynamically -->
            </div>
        </div>
    </div>
</div>

<!-- SEND COMMISION Modal -->
<!-- <div class="modal fade" id="commissionModal" tabindex="-1" role="dialog" aria-labelledby="commissionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="commissionModalLabel">Commission Form</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
          <div class="form-group">
            <label for="name">Account Number</label>
            <input type="number" class="form-control" id="name" name="agent_account_number" placeholder="Enter Value">
          </div>
          <div class="form-group">
            <label for="Commision">Expected Commision</label>
            <input type="text" class="form-control" id="email" name="commision" placeholder="Enter your Value">
          </div>
          <div class="form-group">
            <label for="subject">Unique Code</label>
            <input type="text" class="form-control" id="subject" name="unique_code" placeholder="Enter Value">
            <input type="hidden" class="form-control" value="<?php echo $user_data['id'];?>" name="lender_id">

          </div>
          <div class="form-group">
            <label for="selectOption">Options</label>
            <select class="form-control" id="selectOption">
    <option value="">Select Transaction Code</option> 
            <?php
        $state = $conn->prepare("SELECT * FROM agent_returns where lender_id='$lender_id' ");
        $state->execute();
        $res = $state->get_result();
        while ($rows = $res->fetch_assoc()) {
          echo '<option value="' . $rows['unique_code'] . '">' . $rows['unique_code'] . '</option>';
        }
        ?>
            </select>
          </div>
      
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" name="commision_send">Submit</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </form>
      </div>
    </div>
  </div>
</div> -->




                           <!-- TOP UP Bootstrap Modal -->
<div class="modal fade" id="topUpModal" tabindex="-1" role="dialog" aria-labelledby="topUpModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="topUpModalLabel">Top Up</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form id="topUpForm" action="../PAYMENT/process_topup.php" method="POST">
          <div class="form-group">
            <label for="amount">Amount:</label>
            <input type="text" class="form-control" id="amount" name="amount" required>
          </div>
          <div class="form-group">
            <label for="phoneNumber">Phone Number:</label>
            <input type="number" class="form-control" id="phoneNumber" name="phoneNumber" required>
          </div>
           
            <input type="hidden" class="form-control" id="lender_id" name="lender_id" value="<?php echo $user_data['id'];?>" required>
          <button type="submit" class="btn btn-primary">Submit</button>
        </form>
      </div>
    </div>
  </div>
</div>  
 
<script src="toggle.js"></script>
<script>
    var viewButtons = document.getElementsByClassName("view-button");
    for (var i = 0; i < viewButtons.length; i++) {
        viewButtons[i].addEventListener("click", function () {
            var uniqueCode = this.getAttribute("data-unique-code");

            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("modalBody").innerHTML = this.responseText;
                }
            };
            xhttp.open("GET", "fetch_details.php?unique_code=" + uniqueCode, true);
            xhttp.send();
        });
    }



    function getCustomerDetails() {
    var selectedUniqueCode = document.getElementById("selectOption").value;

    var xhr = new XMLHttpRequest();
    xhr.open("GET", "fetch_commision.php?unique_code=" + selectedUniqueCode, true);
    xhr.onreadystatechange = function() {
      if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
        // Parse the JSON response
        var response = JSON.parse(xhr.responseText);

        // Update the input fields with the customer details
        document.getElementById("name").value = response.agent_account_number;
        document.getElementById("email").value = response.commision;
        document.getElementById("subject").value = response.unique_code;
      }
    };
    xhr.send();
  }

  document.getElementById("selectOption").addEventListener("change", getCustomerDetails);
</script>

    </table> 
 
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