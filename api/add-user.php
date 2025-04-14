<?php
require_once "db.php";

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the JSON data from the request body
    $data = json_decode(file_get_contents("php://input"), true);
    
    // Check if required fields are present
    if (isset($data['name']) && isset($data['email']) && isset($data['password'])) {
        $name = $data['name'];
        $email = $data['email'];
        $password = password_hash($data['password'], PASSWORD_DEFAULT); // Hash the password
        
        // Prepare the SQL statement
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password);
        
        // Execute the statement
        if ($stmt->execute()) {
            echo json_encode([
                "success" => true,
                "message" => "User added successfully",
                "id" => $conn->insert_id
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Failed to add user: " . $stmt->error
            ]);
        }
        
        $stmt->close();
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Missing required fields (name, email, password)"
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method. Use POST."
    ]);
}

$conn->close();
?>