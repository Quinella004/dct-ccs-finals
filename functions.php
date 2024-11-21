<?php  
function connectDatabase(): mysqli {
    // Database configuration
    $config = [
        'servername' => 'localhost',
        'username' => 'root',          // Update if necessary
        'password' => '',              // Update if necessary
        'dbname' => 'dct-ccs-finals',  // Replace with your actual database name
    ];

    // Create a connection
    $conn = new mysqli(
        $config['servername'], 
        $config['username'], 
        $config['password'], 
        $config['dbname']
    );

    // Check for connection errors
    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }

    return $conn;
}
function loginUser(string $email, string $password): array {
    // Initialize an array for errors
    $errors = [];

    // Input validation
    if (empty($email)) {
        $errors['email'] = 'Email Address is required!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email Address is invalid!';
    }

    if (empty($password)) {
        $errors['password'] = 'Password is required!';
    }

    // Return errors if validation fails
    if (!empty($errors)) {
        return ['success' => false, 'errors' => $errors];
    }

    // Establish database connection
    try {
        $conn = connectDatabase();
    } catch (Exception $e) {
        return ['success' => false, 'errors' => ['database' => $e->getMessage()]];
    }

    // Hash the password using MD5
    $hashedPassword = md5($password);

    // Query to check credentials
    $query = "SELECT * FROM users WHERE email = ? AND password = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("ss", $email, $hashedPassword);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if a user is found
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            return ['success' => true, 'user' => $user];
        } else {
            return ['success' => false, 'errors' => ['credentials' => 'Invalid email or password.']];
        }
    } else {
        // Handle query preparation failure
        return ['success' => false, 'errors' => ['query' => 'Failed to prepare the query.']];
    }
}

    // All project functions should be placed here
?>
