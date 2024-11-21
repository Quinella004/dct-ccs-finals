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

    // All project functions should be placed here
?>