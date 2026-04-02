<?php
$mysqli = new mysqli("127.0.0.1", "root", "", "laravel");

$output = "";

if ($mysqli->connect_errno) {
    $output .= "Failed to connect to MySQL: " . $mysqli->connect_error;
} else {
    $res = $mysqli->query("SELECT migration FROM migrations ORDER BY id DESC LIMIT 10");
    while ($row = $res->fetch_assoc()) {
        $output .= $row['migration'] . "\n";
    }

    $res = $mysqli->query("DESCRIBE my_classes");
    $output .= "\nColumns in my_classes:\n";
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $output .= $row['Field'] . "\n";
        }
    } else {
        $output .= "Error DESCRIBE: " . $mysqli->error . "\n";
    }
}

file_put_contents("verify_db.txt", $output);
