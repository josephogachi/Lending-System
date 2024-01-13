
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
 
$updated_commision_balance = mysqli_query($conn, "SELECT * FROM agent_returns WHERE agent_account_number='$acc_no'");
$total_commision = 0;
while ($row = mysqli_fetch_assoc($updated_commision_balance)) {
    $total_commision += $row['expected_commision'];
}
if (isset($_POST['return'])){
  $unique_code=$_POST['unique_code'];
  $lender_id=$_POST['lender_id'];
  $agent_acc_no=$_POST['agent_account_number'];
  $amount_sent = $_POST['total_amount'];
  $commision = $_POST['expected_commision'];
  $updated_total = $_POST['updated_total'];

  // $ID = $_POST['customer_id'];

  $statement= $conn->prepare("INSERT into agent_returns (unique_code,lender_id,agent_account_number,total_amount,expected_commision,updated_total) VALUES (?,?,?,?,?,?)");
  $statement->bind_param("siiddd",$unique_code,$lender_id,$agent_acc_no,$amount_sent,$commision,$updated_total);
  $statement->execute();
  $statement->close();
  header("Location: ./agent.php");
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

    <title>Agents Dashboard</title>
</head>
<body>

    <div class="sidemenu">
        <div class="title">
            <h1>Agent</h1></div>
            <button class="menu-toggle-button">&#9776;</button>

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
    <!-- <button class="menu-toggle-button">&#9776;</button> -->

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
        <a href="agent.php">Dashboard</a>
     </div>
     <div class="main-buttons">
    <div class="display-commision">
        <div class="commision-box">
        <img src="assets/sack-dollar.png" alt=""><br>
        <input type="text" value="<?php echo $total_commision;?>" readonly>
        <p>My Commision</p>
    </div>
        <div class="withdraw">
            <button>Withdraw Commision</button>
        </div>
        <div class="send-back">
        <button type="button" class="allocate-btn" data-toggle="modal" data-target="#sendModal">Send Money to Lender</button>

        </div>
    </div>
 
        </div>
        </div>
         <!-- Send Bootstrap Modal -->
      <div class="modal fade" id="sendModal" tabindex="-1" role="dialog" aria-labelledby="sendModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="sendModalLabel">Send to Lender</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
          <div class="form-group">
            <label for="input1">Unique Code</label>
            <input type="text" class="form-control" id="input1" placeholder="Enter value" name="unique_code" readonly>
          </div>
          <div class="form-group">
            <label for="input2">Lender ID</label>
            <input type="text" class="form-control" id="input2" placeholder="Enter value" name="lender_id" readonly>
          </div>
          <div class="form-group">
            <label for="input3">Agent Account Number</label>
            <input type="text" class="form-control" id="input3" placeholder="Enter value" name="agent_account_number" readonly>
          </div>
          <div class="form-group">
            <label for="input4">Total Amount Sent</label>
            <input type="text" class="form-control" id="input4" placeholder="Enter value" name="total_amount" readonly>
          </div>
          <div class="form-group">
            <label for="input5">Expected Commission</label>
            <input type="text" class="form-control" id="input5" placeholder="Enter value" name="expected_commision" readonly>
          </div>
          <div class="form-group">
        <label for="input6">Updated Total</label>
        <input type="text" class="form-control" id="input6" placeholder="Updated Total" name="updated_total" readonly>
         </div>

          <div class="form-group">
            <label for="select">Dropdown Select</label>
            <select class="form-control" id="select" onchange="fetchData(this.value)">
    <option value="">Select Transaction Code</option> 
        <?php
        $state = $conn->prepare("SELECT * FROM lender_transactions where agent_account_number='$acc_no' ");
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
        <button type="submit" class="btn btn-primary" name="return">Send</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </form>
      </div>
    </div>
  </div>
</div>

 
        <div class="content-2">
            <div class="money-returned">
                <div class="title">
                    <h4>Loans Summary</h4>
                    <hr>
                    <a href="customer.php">View All</a>
                </div>
                <div class="table">
                    <table>
            
                    <tr>
                        <th>ID</th>
                        <th>Customer Number</th>
                        <th>Amount Lent</th>
                        <th class="table-hide">Total Amount(+Interest) </th>
                        <th class="table-hide">Time Allocated</th>
                        <th>Unique Code</th>
                    </tr>
                    <?php
            $id_count = 0;
            $account_no=$user_data['account_number'];
            $stmt = $conn->prepare("SELECT * from customer_money where agent_id='$id'");
            $stmt->execute();
            $result = $stmt->get_result();
            $display_limit = 6;  
            $row_count = 0;  
            while ($row = $result->fetch_assoc()) {
                $row_count++;

                if ($row_count > $display_limit) {
                    break;   
                }
             
            ?>
                  
                    
                <tr>
                <tr>
                <td><?php echo $id_count; ?></td>
                <td><?php echo $row['customer_number']; ?></td>
                <td><?php echo $row['amount_lent']; ?></td>
                <td class="table-hide"><?php echo $row['total_amount']; ?></td>
                <td class="table-hide"><?php echo $row['time_allocated']; ?></td>

                <td><?php echo $row['unique_code']; ?></td>

            </tr>
                </tr>
            <?php $id_count = $id_count + 1 ;} ?>

                </table>
                </div>
                    
                <!-- </table> -->
              <script src="toggle.js"></script>
           <!-- Modal Updating Javascript -->
<script>
  function fetchData(selectedValue) {
    $.ajax({
      url: 'fetch_data.php',
      method: 'POST',
      data: { selectedValue: selectedValue },
      success: function(response) {
        document.getElementById('input1').value = response.uniqueCode;
        document.getElementById('input2').value = response.lenderID;
        document.getElementById('input3').value = response.agentAccountNumber;
        document.getElementById('input4').value = response.totalAmountSent;
        document.getElementById('input5').value = calculateExpectedCommission(response.totalAmountSent);


           // Calculate and populate the Updated Total field
      var totalAmount = parseFloat(response.totalAmountSent);
      var expectedCommission = parseFloat(calculateExpectedCommission(response.totalAmountSent));
      var updatedTotal = (totalAmount - expectedCommission).toFixed(2);
      document.getElementById('input6').value = updatedTotal;
      },
      error: function() {
        // Handle errors if any
      }
    });
  }

  function calculateExpectedCommission(totalAmount) {
    // Calculate 3% of the total amount
    var commission = totalAmount * 0.03;
    return commission.toFixed(2); // Round to 2 decimal places if needed
  }
</script>
<!-- End of Modal Updating Javascript --> 
        
 

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