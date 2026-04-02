<?php
$mysqli = new mysqli("127.0.0.1", "root", "", "laravel");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

$emails = ["'pradeep@gmail.com'", "'pooja@gmail.com'"];
$sql = "SELECT id, email, user_type FROM users WHERE email IN (" . implode(",", $emails) . ")";
$res = $mysqli->query($sql);

$output = "";
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $output .= "ID: " . $row['id'] . " | EMAIL: " . $row['email'] . " | ROLE: " . $row['user_type'] . "\n";
    }
} else {
    $output .= "ERROR: " . $mysqli->error;
}
file_put_contents('user_role_check.txt', $output);
echo "SUCCESS";
