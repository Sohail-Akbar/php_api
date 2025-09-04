<?php
// Helper function for Bearer Token validation
function validateBearerToken()
{
    global $db;
    $headers = getallheaders();
    // Check Authorization header
    if (!isset($headers['Authorization'])) {
        sendErrorResponse("Authorization header is required", 401);
    }

    $authHeader = $headers['Authorization'];
    if (strpos($authHeader, 'Bearer ') !== 0) {
        sendErrorResponse("Invalid Authorization format. Expected 'Bearer <token>'", 401);
    }

    // Extract token
    $token = substr($authHeader, 7);

    // Check token in the database
    $user = $db->select_one('users', 'id,agency_id,token,user_type', ['token' => $token]);
    if (!$user) {
        sendErrorResponse("Invalid or expired token", 403);
    }

    return $user;
}
