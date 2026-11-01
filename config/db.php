<?php
/**
 * Food Management System (FMS) - Database Configuration
 * Centralized connection file for MySQL (food_db)
 */

$db_host = '127.0.0.1';
$db_user = 'root';
$db_pass = '';
$db_name = 'food_db';
$db_port = 3306;

// Create connection with error suppression for graceful offline mode
$conn = @new mysqli($db_host, $db_user, $db_pass, $db_name, $db_port);

// Connection status
$db_connected = !$conn->connect_error;

// Helper function to return standardized JSON response
if (!function_exists('send_response')) {
    function send_response($success, $data = [], $message = '') {
        header('Content-Type: application/json; charset=UTF-8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');
        echo json_encode([
            'success' => $success,
            'message' => $message,
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
        exit();
    }
}

