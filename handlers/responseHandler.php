<?php
function returnSuccess($data = [], $message = "Success")
{
    echo json_encode([
        "status" => "success",
        "message" => $message,
        "data" => $data
    ]);
    die;
}

function returnError($message = "An error occurred")
{
    echo json_encode([
        "status" => "error",
        "message" => $message
    ]);
    die;
}

// Function to validate email
function validateEmail($email)
{
    // Check if the email is valid
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}
