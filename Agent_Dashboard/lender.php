
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
if (isset($_POST['send'])){
    $customer_number=$_POST['customer_number'];
    $amount_lent = $_POST['amount_lent'];
    $unique_code = $_POST['unique_code'];
    $expected_interest=$_POST['expected_interest'];
    $agent_id=$_POST['agent_id'];
    $total_amount=$_POST['total_amount'];
    $amount=$_POST['amount'];
    if ($amount_lent > $amount) {
      echo"
      <script>
      alert('Insufficient Balance to make this transaction');
      </script>
      ";}
 
    // $updated_balance=$user_trans['lent_amount']
   
else{ 
  $updated_balance=$amount-$amount_lent;
  $interest=$amount_lent*0.12;
  echo $interest;

  // $stm= UPDATE `lender_transactions` SET lent_amount`='[value-4]' WHERE 1;
  $updateQuery = "UPDATE lender_transactions SET lent_amount = $updated_balance WHERE unique_code = '$unique_code'";
  mysqli_query($conn, $updateQuery);

    // $time = date('H:i:s');
    $statement= $conn->prepare("INSERT into customer_money (customer_number,amount_lent,unique_code,expected_interest,total_amount,agent_id) VALUES (?,?,?,?,?,?)");
    $statement->bind_param("idsddi",$customer_number,$amount_lent,$unique_code,$expected_interest,$total_amount,$agent_id);
    $statement->execute();
    $statement->close();
    $state= $conn->prepare("INSERT into updated_values (agent_id,updated_balance,unique_code,expected_interest,total_amount) VALUES (?,?,?,?,?)");
    $state->bind_param("idsdi",$agent_id,$updated_balance,$unique_code,$expected_interest,$total_amount);
    $state->execute();
    $state->close();
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<!-- Bootstrap JavaScript -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="agent.css">

    <title>Agents Dasboard</title>
</head>
<body>

    <div class="sidemenu">
        <div class="title">
            <h1>Agent</h1>  </div>
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
                <h2>Money Allocated</h2>
                <span>Hi <?php echo $user_data['username'];?></span>
            </div>
        </div>
    </div>
    
         
        
    </div> 
     <br><br><br><br><br><br>
     <div class="dashboard" style="margin-left:21%;margin-top:0; margin-bottom:2%; font-weight:bold;";>
        <a href="agent.php">Dashboard</a>
     </div>
     <div class="table">

<table>
 <thead class="head">
   <tr>
     <td>ID</td>
     <td>Money Allocated</td>
     <td>Time Allocated</td>
     <td>Unique Code</td>
     <!-- <td>Profit Generated</td> -->
     <td>Allocate</td>
     <!-- <td>Send to Lender</td> -->
   </tr>
 </thead>
 <tbody>
   <?php
   $id_count = 0;
   $account_no=$user_data['account_number'];
   $stmt = $conn->prepare("SELECT * from lender_transactions where agent_account_number='$account_no'");
   $stmt->execute();
   $result = $stmt->get_result();
   while ($row = $result->fetch_assoc()) {
     
     ?>
     <tr>
       <td><?php echo $id_count; ?></td>
       <td><?php echo $row['lent_amount']; ?></td>
       <td><?php echo $row['time_allocated']; ?></td>
       <td><?php echo $row['unique_code']; ?></td>
       <!-- <td>12344</td> -->
       <td>
         <button class="allocate-btn" data-toggle="modal" data-target="#allocationModal-<?php echo $row['unique_code']; ?>" data-row-id="<?php echo $id_count; ?>">ALLOCATE</button>
       </td>
   
     </tr>

        <!-- Bootstrap Modal -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="allocationForm">
        <div class="modal fade" id="allocationModal-<?php echo $row['unique_code']; ?>" tabindex="-1" role="dialog" aria-labelledby="allocationModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="allocationModalLabel-<?php echo $row['unique_code']; ?>">Allocate Funds</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label for="customerNumber">Customer Number</label>
                  <input type="text" class="form-control" id="customerNumber" name="customer_number" required>
                </div>
                <div class="form-group">
                  <label for="amountToLend">Amount to Lend</label>
                  <input type="number" class="form-control amount-input" id="amountToLend" name="amount_lent" min="0" step="0.01" required>
                </div>
                <div class="form-group">
                  <label for="amountToLend">Unique Code</label>
                  <input type="text" class="form-control" value="<?php echo $row['unique_code']; ?>" name="unique_code" readonly>
                </div>
                <div class="form-group">
                  <label for="expectedInterest">Expected Interest</label>
                  <input type="number" class="form-control expected-interest" id="expectedInterest" name="expected_interest" min="0" step="0.01" readonly>
                </div>

                <div class="form-group">
                  <label for="totalAmountReturned">Total Amount Returned</label>
                  <input type="number" class="form-control total-amount" id="totalAmountReturned" name="total_amount" readonly>
                </div>
                <input type="hidden" class="form-control" value="<?php echo $user_data['id']; ?>" name="agent_id" readonly>
                <input type="hidden" class="form-control" value="<?php echo $row['lent_amount']; ?>" name="amount" readonly>
                
              </div>
              <div class="modal-footer">
                <button type="submit" name="send">Allocate</button>
              </div>
            </div>
          </div>
        </div>
      </form>
      <?php
      $id_count = $id_count + 1;
    } ?>
  </tbody>
</table>

<script>
  // Get all amount input fields
  var amountInputs = document.querySelectorAll('.amount-input');

  // Attach event listeners to each input field
  amountInputs.forEach(function(input) {
    input.addEventListener('input', function() {
      var amount = parseFloat(this.value);
      var expectedInterest = amount * 0.12;
      var totalAmount = amount + expectedInterest;

      // Update the corresponding fields in the current row
      var modal = this.closest('.modal');
      modal.querySelector('.expected-interest').value = expectedInterest.toFixed(2);
      modal.querySelector('.total-amount').value = totalAmount.toFixed(2);
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