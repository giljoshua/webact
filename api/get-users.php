<?php
require_once "db.php";

// Check request method
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if ID parameter is set
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        
        // Prepare SQL statement to get a specific user
        $stmt = $conn->prepare("SELECT id, name, email, created_at FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            echo json_encode([
                "success" => true,
                "data" => $user
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "User not found"
            ]);
        }
        
        $stmt->close();
    } else {
        // Get all users
        $sql = "SELECT id, name, email, created_at FROM users";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            $users = [];
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
            echo json_encode([
                "success" => true,
                "data" => $users
            ]);
        } else {
            echo json_encode([
                "success" => true,
                "data" => [],
                "message" => "No users found"
            ]);
        }
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method. Use GET."
    ]);
}

$conn->close();
?>
