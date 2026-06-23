<?php
include 'new_config.php';

// Get the recipe ID from the URL parameter
$recipeid = isset($_GET['id']) ? intval($_GET['id']) : 0;

try {
    // Prepare and execute query to fetch recipe details
    $query = "SELECT title, poster, shortdesc, ingredients, directions FROM recipes WHERE recipeid = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$recipeid]);
    $recipe = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if recipe exists
    if (!$recipe) {
        die('Recipe not found');
    }

    // Sanitize recipe details to prevent XSS
    $title = htmlspecialchars($recipe['title']);
    $poster = htmlspecialchars($recipe['poster']);
    $shortdesc = htmlspecialchars($recipe['shortdesc']);
    $ingredients = nl2br(htmlspecialchars($recipe['ingredients']));
    $directions = nl2br(htmlspecialchars($recipe['directions']));

    // Output recipe details
    echo "<h2>$title</h2>\n";
    echo "Posted by $poster <br><br>\n";
    echo "$shortdesc <br><br>\n";
    echo "<h3>Ingredients:</h3>\n";
    echo $ingredients . "<br><br>\n";
    echo "<h3>Directions:</h3>\n";
    echo $directions . "<br><br>\n";

    // Count comments for the recipe
    $query = "SELECT COUNT(commentid) FROM comments WHERE recipeid = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$recipeid]);
    $count = $stmt->fetchColumn();

    // Output comments count and options
    if ($count == 0) {
        echo "No comments posted yet.&nbsp;&nbsp;\n";
        echo "<a href=\"index.php?content=newcomment&id=$recipeid\">Add a comment</a>\n";
        echo "&nbsp;&nbsp;&nbsp;<a href=\"print.php?id=$recipeid\" target=\"_blank\">Print recipe</a>\n";
        echo "<hr>\n";
    } else {
        echo "$count&nbsp;comments posted.&nbsp;&nbsp;\n";
        echo "<a href=\"index.php?content=newcomment&id=$recipeid\">Add a comment</a>\n";
        echo "&nbsp;&nbsp;&nbsp;<a href=\"print.php?id=$recipeid\" target=\"_blank\">Print recipe</a>\n";
        echo "<hr>\n";
        echo "<h2>Comments:</h2>\n";

        // Fetch and output comments
        $query = "SELECT date, poster, comment FROM comments WHERE recipeid = ? ORDER BY commentid DESC";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$recipeid]);
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($comments as $comment) {
            $date = $comment['date'];
            $poster = $comment['poster'];
            $commentText = nl2br(htmlspecialchars($comment['comment']));

            echo "$date - posted by $poster\n";
            echo "<br>\n";
            echo "$commentText\n";
            echo "<br><br>\n";
        }
    }
} catch(PDOException $e) {
    // If an error occurs, display error message
    die("Error: " . $e->getMessage());
}
?>
