<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $flag = $_POST['flag'] ?? '';
    if (isset($_POST['flag']) && $_POST['flag'] == 'bookingForm') {
        $name     = $_POST['name'] ?? '';
        $phone    = $_POST['phone'] ?? '';
        $checkin  = $_POST['checkin'] ?? '';
        $checkout = $_POST['checkout'] ?? '';
        $rooms    = $_POST['rooms'] ?? '';
        $adults   = $_POST['adults'] ?? '';
        $children = $_POST['children'] ?? '';
    }

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'avinash.mogulas@gmail.com';
        $mail->Password   = 'npjvobfhyaryrrpg';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Sender & Receiver

        switch ($flag) {
            case 'bookingForm':
                $toEmail = 'avinash8564kumar@gmail.com';
                $toName = 'Admin';
                $fromEmail = 'avinash.mogulas@gmail.com';
                $fromName = 'Booking Enquiry in Hitech City';
                $subject = 'New Booking Enquiry';
                $fields = [
                    'Name' => $name,
                    'Phone' => $phone,
                    'Check-in' => $checkin,
                    'Check-out' => $checkout,
                    'Rooms' => $rooms,
                    'Adults' => $adults,
                    'Children' => $children,
                ];
                break;
            default:
                echo json_encode(['status' => 'error', 'message' => 'Invalid Form Submit Request']);
                exit;
        }

        $mail->setFrom($fromEmail, $fromName);
        $mail->addAddress($toEmail, $toName);

        // Email Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $body = "<h2>$subject</h2>";
        $body .= "<table border='1' cellpadding='10' cellspacing='0' style='border-collapse: collapse; width: 100%;'>";
        foreach ($fields as $key => $value) {
            $body .= "
        <tr>
            <th style='background-color: #f2f2f2;'>$key</th>
            <td>$value</td>
        </tr>";
        }
        $body .= "</table>";

        $mail->Body = $body;

        $mail->send();
        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $mail->ErrorInfo]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid Request']);
}
