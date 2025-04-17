<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Server settings
require('phpmailer/PHPMailerAutoload.php');

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

    try {

        $mail = new PHPMailer(); // create a new object
        //$mail->IsSMTP(); // enable SMTP
        $mail->SMTPDebug = 2; // debugging: 1 = errors and messages, 2 = messages only
        //$mail->SMTPAuth = true; // authentication enabled
        //$mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for Gmail
        $mail->Host = "localhost";
        $mail->Port = 25;
        $mail->IsHTML(true);

        // Sender & Receiver

        switch ($flag) {
            case 'bookingForm':
                $toEmail = 'seraikitchens@deccanserai.com, notification@internetmoguls.com, ishaan@internetmoguls.com, avinash.k@internetmoguls.com';
                $fromEmail = 'operationsdsg@deccanserai.com';
                $fromName = 'Booking From Hitechcity';
                $subject = 'New Booking';
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

        // Convert to array if needed
        $toEmails = is_array($toEmail) ? $toEmail : explode(',', $toEmail);

        // Loop and add all addresses
        foreach ($toEmails as $email) {
            $mail->addAddress(trim($email));
        }   

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
