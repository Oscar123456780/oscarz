<?php
// Function to get the visitor's IP address
function getVisitorIP() {
    // Check for known proxy headers to get the original IP if available
    $proxyHeaders = array(
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_X_CLUSTER_CLIENT_IP',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED',
        'REMOTE_ADDR',
    );

    foreach ($proxyHeaders as $header) {
        if (array_key_exists($header, $_SERVER) && !empty($_SERVER[$header])) {
            $ipList = explode(',', $_SERVER[$header]);
            $ip = trim($ipList[0]);
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }
    }

    return 'UNKNOWN'; // If no valid IP found, return a default value
}

// Log the visitor's IP address to ip.txt
function logVisitorIP() {
    $ip = getVisitorIP();
    $logFile = 'ip.txt';

    // Open the log file in append mode
    if ($handle = fopen($logFile, 'a')) {
        fwrite($handle, date('Y-m-d H:i:s') . ' - ' . $ip . PHP_EOL);
        fclose($handle);
    } else {
        error_log('Failed to open log file: ' . $logFile, 0);
    }
}

// Call the logVisitorIP function to log the visitor's IP
logVisitorIP();
?>
