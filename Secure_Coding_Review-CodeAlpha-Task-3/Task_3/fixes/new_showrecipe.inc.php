<?php
include 'new_config.php'; // Assuming this file contains database connection details

// Get the recipe ID from the URL parameter and sanitize it
$recipeid = isset($_GET['id']) ? intval($_GET['id']) : 0;

try {
    // Prepare and execute query to fetch recipe details using PDO to prevent SQL injection
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

    // Prepare and execute query to count comments for the recipe using PDO
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

        // Determine current page number
        $thispage = isset($_GET['page']) ? intval($_GET['page']) : 1;

        // Set up pagination
        $recordsperpage = 5;
        $offset = ($thispage - 1) * $recordsperpage;

        // Calculate total number of pages
        $totpages = ceil($count / $recordsperpage);

        // Prepare and execute query to fetch comments with pagination using PDO
        $query = "SELECT date, poster, comment FROM comments WHERE recipeid = ? ORDER BY commentid DESC LIMIT ?, ?";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(1, $recipeid, PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, PDO::PARAM_INT);
        $stmt->bindValue(3, $recordsperpage, PDO::PARAM_INT);
        $stmt->execute();
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Output comments
        foreach ($comments as $comment) {
            $date = htmlspecialchars($comment['date']);
            $poster = htmlspecialchars($comment['poster']);
            $commentText = nl2br(htmlspecialchars($comment['comment']));

            echo "$date - posted by $poster\n";
            echo "<br>\n";
            echo "$commentText\n";
            echo "<br><br>\n";
        }

        // Output pagination links
        echo "GoTo: ";
        if ($thispage > 1) {
            $prevpage = $thispage - 1;
            echo "<a href=\"index.php?content=showrecipe&id=$recipeid&page=$prevpage\">Previous</a> ";
        } else {
            echo "Previous ";
        }

        for ($page = 1; $page <= $totpages; $page++) {
            if ($page == $thispage) {
                echo "$page ";
            } else {
                echo "<a href=\"index.php?content=showrecipe&id=$recipeid&page=$page\">$page</a> ";
            }
        }

        if ($thispage < $totpages) {
            $nextpage = $thispage + 1;
            echo "<a href=\"index.php?content=showrecipe&id=$recipeid&page=$nextpage\">Next</a>";
        } else {
            echo "Next";
        }
    }
} catch (PDOException $e) {
    // If an error occurs, display error message
    die("Error: " . $e->getMessage());
}
?>
