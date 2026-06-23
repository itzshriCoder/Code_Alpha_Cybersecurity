<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Recipe Center</title>
    <link rel="stylesheet" type="text/css" href="print.css" />
</head>
<body>
<?php include 'config.php'; ?>
<?php
$recipeid = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($recipeid <= 0) {
    die('Invalid recipe ID');
}

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT title, poster, shortdesc, ingredients, directions FROM recipes WHERE recipeid = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$recipeid]);
    
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$row) {
        die('Recipe not found');
    }

    $title = htmlspecialchars($row['title']);
    $poster = htmlspecialchars($row['poster']);
    $shortdesc = htmlspecialchars($row['shortdesc']);
    $ingredients = nl2br(htmlspecialchars($row['ingredients']));
    $directions = nl2br(htmlspecialchars($row['directions']));

    echo "<h2>$title</h2>\n";
    echo "Posted by $poster <br>\n";
    echo "$shortdesc \n";
    echo "<h3>Ingredients:</h3>\n";
    echo $ingredients . "<br>\n";
    echo "<h3>Directions:</h3>\n";
    echo $directions . "\n";
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
</body>
</html>
