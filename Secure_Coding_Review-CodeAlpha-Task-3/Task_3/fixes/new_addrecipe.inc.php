<?php include 'config.php'; ?>
<div id="main">
<div id='preview'>
<?php
$title = $_POST['title'];
$poster = $_POST['poster'];
$shortdesc = $_POST['shortdesc'];
$ingredients = htmlspecialchars($_POST['ingredients']);
$directions = htmlspecialchars($_POST['directions']);

if (trim($poster == ''))
{
    echo "<h2>Sorry, each recipe must have a poster</h2>\n";
} else {
    // Prepare the SQL query using placeholders
    $query = "INSERT INTO recipes (title, shortdesc, poster, ingredients, directions) " .
              " VALUES (?, ?, ?, ?, ?)";

    // Prepare the statement
    $stmt = $pdo->prepare($query);

    // Bind parameters
    $stmt->bindParam(1, $title, PDO::PARAM_STR);
    $stmt->bindParam(2, $shortdesc, PDO::PARAM_STR);
    $stmt->bindParam(3, $poster, PDO::PARAM_STR);
    $stmt->bindParam(4, $ingredients, PDO::PARAM_STR);
    $stmt->bindParam(5, $directions, PDO::PARAM_STR);

    // Execute the statement
    $result = $stmt->execute();

    if ($result)
       echo "<h2>Recipe posted</h2>\n";
    else
       echo "<h2>Sorry, there was a problem posting your recipe</h2>\n";
}
?>
</div>
</div>
