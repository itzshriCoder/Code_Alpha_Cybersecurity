<?php include 'config.php'; ?>
<div id="main">
    <div id='preview'>
        <?php
        try {
            // Sanitize the search input
            $search = isset($_GET['searchFor']) ? htmlspecialchars($_GET['searchFor']) : '';

            // Establish a connection to the database using PDO
            $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Prepare the SQL query with a parameterized statement to prevent SQL injection
            $query = "SELECT recipeid, title, shortdesc FROM recipes WHERE title LIKE CONCAT('%', ?, '%')";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$search]);

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo "<h1>Search Results</h1><br><br>\n";

            if (count($result) == 0) {
                echo "<h2>Sorry, no recipes were found with '$search' in them.</h2>";
            } else {
                echo "<h2>Recipes matching '$search':</h2><br><br>";
                foreach ($result as $row) {
                    $recipeid = $row['recipeid'];
                    $title = $row['title'];
                    $shortdesc = $row['shortdesc'];
                    echo "<a href=\"index.php?content=showrecipe&id=$recipeid\">" . htmlspecialchars($title) . "</a><br>\n";
                    echo htmlspecialchars($shortdesc) . "<br><br>\n";
                }
            }
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
        ?>
    </div>
</div>
