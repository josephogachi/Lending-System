<?php
require '../Functions/connect.php';

$uniqueCode = $_GET['unique_code'];

$state = $conn->prepare("SELECT * FROM agent_returns WHERE unique_code = ?");
$state->bind_param("s", $uniqueCode);
$state->execute();
$res = $state->get_result();

$totalAmountAgentReturns = 0;
while ($row = $res->fetch_assoc()) {
    $totalAmountAgentReturns += $row['total_amount'];
}

$state2 = $conn->prepare("SELECT lent_amount AS total_amount_lender_transactions FROM lender_transactions WHERE unique_code = ?");
$state2->bind_param("s", $uniqueCode);
$state2->execute();
$res2 = $state2->get_result();
$totalAmountLenderTransactions = $res2->fetch_assoc()['total_amount_lender_transactions'];

// Prepare the content to be displayed in the modal
$content = "<h2>Unique Code: $uniqueCode</h2>";
$content .= "<p>Total Amount Returned (Agent Returns): $totalAmountAgentReturns</p>";
$content .= "<p>Lent Amount: $totalAmountLenderTransactions</p>";
// $content .= "<h3>Other Table Details:</h3>";

echo $content;
?>
