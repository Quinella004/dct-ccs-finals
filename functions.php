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
function fetchSubjects(): array {
    $conn = connectDatabase();
    $result = $conn->query("SELECT * FROM subjects ORDER BY id ASC");
    $subjects = $result->fetch_all(MYSQLI_ASSOC);
    $conn->close();
    return $subjects;
}
function generateValidStudentId($original_id) {
    // Truncate to the first 4 characters
    return substr($original_id, 0, 4);
}
function validateStudentData($student_data) {
    $errors = [];
    if (empty($student_data['student_id'])) {
        $errors[] = "Student ID is required.";
    }
    if (empty($student_data['first_name'])) {
        $errors[] = "First Name is required.";
    }
    if (empty($student_data['last_name'])) {
        $errors[] = "Last Name is required.";
    }

    // Removed the var_dump debug
    return $errors;
}
function checkDuplicateStudentData($student_data) {
    $connection = connectDatabase();
    $query = "SELECT * FROM students WHERE student_id = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param('s', $student_data['student_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return "Student ID already exists.";
    }

    // Removed the var_dump debug
    return '';
}
function generateUniqueIdForStudents() {
    $connection = connectDatabase();

    // Find the maximum current ID and add 1 to it
    $query = "SELECT MAX(id) AS max_id FROM students";
    $result = $connection->query($query);
    $row = $result->fetch_assoc();
    $max_id = $row['max_id'];

    $connection->close();

    return $max_id + 1; // Generate the next unique ID
}
function renderAlert($messages, $type = 'danger') {
    if (empty($messages)) {
        return '';
    }
    // Ensure messages is an array
    if (!is_array($messages)) {
        $messages = [$messages];
    }
}
    // All project functions should be placed here
?>
