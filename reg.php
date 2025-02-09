<?php
session_start();
$host = "localhost";
$user = "root";  // Change if needed
$pass = "";  // Add your MySQL password if required
$dbname = "user_db";

// Connect to database
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Handle Registration
if (isset($_POST['signUp'])) {
    $first_name = $_POST['fName'];
    $last_name = $_POST['lName'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash password
    $phone = $_POST['phone'];

    // Check if email already exists
    $checkEmail = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $result = $checkEmail->get_result();
    
    if ($result->num_rows > 0) {
        echo "Error: Email already registered!";
    } else {
        // Insert into database
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, phone) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $first_name, $last_name, $email, $password, $phone);
        if ($stmt->execute()) {
            echo "Registration Successful!";
        } else {
            echo "Error: Could not register!";
        }
        $stmt->close();
    }
    $checkEmail->close();
}

// Handle Login
if (isset($_POST['signIn'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user['first_name'];
            echo "Login Successful! Welcome " . $_SESSION['user'];
        } else {
            echo "Error: Invalid email or password!";
        }
    } else {
        echo "Error: User not found!";
    }
    $stmt->close();
}

$conn->close();
?>
