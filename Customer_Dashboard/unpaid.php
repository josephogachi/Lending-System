<?php
require '../Functions/connect.php';
echo "Hello";

$currentTimestamp = time(); // Get the current timestamp
echo $currentTimestamp;
// Retrieve unpaid loans that are not in the paid loans table
$query = "SELECT * FROM customer_money cm
          WHERE NOT EXISTS (
              SELECT 1 FROM customer_returns cr
              WHERE cr.unique_code = cm.unique_code
          )
          AND TIMESTAMPDIFF(MINUTE, cm.time_allocated, NOW()) >= 1440";

$result = mysqli_query($conn, $query);

if ($result) {
  while ($row = mysqli_fetch_assoc($result)) {
    $loanId = $row['unique_code'];
    $loanAmount = $row['amount_lent'];
    $loanTimestamp = strtotime($row['time_allocated']);

    $timeDifference = $currentTimestamp - $loanTimestamp; // Calculate the time difference in seconds

    $hoursDifference = floor($timeDifference / 3600); // Convert time difference to hours

    // Check if loan exceeds 24 hours
    if ($hoursDifference >= 24) {
      $increasePercentage = 12; // 12% increase in the loan amount
      $increaseAmount = $loanAmount * ($increasePercentage / 100);
      $newLoanAmount = $loanAmount + $increaseAmount;

      // Update the loan amount in the customer_money table
      // $updateQuery = "UPDATE customer_money SET amount_lent = $newLoanAmount WHERE unique_code = $loanId";
      // mysqli_query($conn, $updateQuery);
    }

    // Print the loan details
    echo "Loan ID: " . $loanId . "<br>";
    echo "Loan Amount: $" . $loanAmount . "<br>";
    if (isset($newLoanAmount)) {
      echo "Updated Loan Amount: $" . $newLoanAmount . "<br>";
    }
    echo "---------------------------<br>";
  }
} else {
  echo "Error: " . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
?>
