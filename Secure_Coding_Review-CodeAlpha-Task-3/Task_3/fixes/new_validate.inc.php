<?php
include 'new_config.php'; 

// Get user inputs from POST data and sanitize them
$userid = isset($_POST['userid']) ? $_POST['userid'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : ''; // No need to sanitize since it will be hashed

try {
    // Prepare and execute query using PDO to prevent SQL injection
    $query = "SELECT userid FROM users WHERE userid = :userid AND password = PASSWORD(:password)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':userid', $userid, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->execute();
    
    // Check if user exists
    if ($stmt->rowCount() == 0) {
        echo "<h2>Sorry, your user account was not validated.</h2><br>\n";
        echo "<a href=\"index.php?content=login\">Try again</a><br>\n";
        echo "<a href=\"index.php\">Return to Home</a>\n";
    } else {
        // Start session and set user as validated
        session_start();
        $_SESSION['valid_recipe_user'] = $userid;
        echo "<h2>Your user account has been validated, you can now post recipes and comments</h2><br>\n";
        echo "<a href=\"index.php\">Return to Home</a>\n";
    }
} catch (PDOException $e) {
    // If an error occurs, display error message
    die("Error: " . $e->getMessage());
}
?>
