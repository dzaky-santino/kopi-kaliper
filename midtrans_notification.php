<?php
include 'components/connect.php';
require_once 'midtrans-php-master/Midtrans.php';

\Midtrans\Config::$serverKey = 'SB-Mid-server-xMXY_YG8WTYcygPT4-w7sM0O';
\Midtrans\Config::$isProduction = false;
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

$payload = file_get_contents('php://input');
$notification = json_decode($payload);

$order_id = $notification->order_id;
$transaction_status = $notification->transaction_status;
$fraud_status = $notification->fraud_status;

if ($transaction_status == 'capture') {
    if ($fraud_status == 'challenge') {
        // Update payment status to pending
        $status_pembayaran = 'pending';
    } else if ($fraud_status == 'accept') {
        // Update payment status to success
        $status_pembayaran = 'sudah bayar';
    }
} else if ($transaction_status == 'settlement') {
    // Update payment status to success
    $status_pembayaran = 'sudah bayar';
} else if ($transaction_status == 'cancel' || $transaction_status == 'deny' || $transaction_status == 'expire') {
    // Update payment status to failed
    $status_pembayaran = 'gagal bayar';
} else if ($transaction_status == 'pending') {
    // Update payment status to pending
    $status_pembayaran = 'pending';
}

$update_order = $conn->prepare("UPDATE `pesanan` SET status_pembayaran = ? WHERE order_id = ?");
$update_order->execute([$status_pembayaran, $order_id]);
