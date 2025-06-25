<?php
session_start();
require_once('dbconnect.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

function generateEsewaSignature($secretKey, $amount, $transactionUuid, $merchantCode)
{
    $signatureString = "total_amount=$amount,transaction_uuid=$transactionUuid,product_code=$merchantCode";
    $hash = hash_hmac('sha256', $signatureString, $secretKey, true);
    return base64_encode($hash);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay_order'])) {
    $amount = $_POST['amount'] ?? '';
    $productName = $_POST['productName'] ?? '';
    $orderId = $_POST['order_id'] ?? '';

    if (empty($amount) || empty($productName) || empty($orderId)) {
        die('Missing required parameters.');
    }

    // eSewa configuration
    $merchantCode = "EPAYTEST";
    $secretKey = "8gBm/:&EnhH.1/q";
    $transactionUuid = uniqid("txn-", true);
    $successUrl = "#";
    $failureUrl = "#";

    // Save transaction UUID in session (optional)
    $_SESSION['txn_uuid'] = $transactionUuid;
    $_SESSION['order_id'] = $orderId;

    $signature = generateEsewaSignature($secretKey, $amount, $transactionUuid, $merchantCode);

    $esewaConfig = [
        "amount" => $amount,
        "tax_amount" => "0",
        "total_amount" => $amount,
        "transaction_uuid" => $transactionUuid,
        "product_code" => $merchantCode,
        "product_service_charge" => "0",
        "product_delivery_charge" => "0",
        "success_url" => $successUrl,
        "failure_url" => $failureUrl,
        "signed_field_names" => "total_amount,transaction_uuid,product_code",
        "signature" => $signature,
    ];

    $paymentUrl = "https://rc-epay.esewa.com.np/api/epay/main/v2/form";

    echo "<form id='esewaForm' method='POST' action='$paymentUrl'>";
    foreach ($esewaConfig as $key => $value) {
        echo "<input type='hidden' name='$key' value='$value'>";
    }
    echo "</form>";
    echo "<script>document.getElementById('esewaForm').submit();</script>";
    exit;
}
