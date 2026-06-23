<?php include 'config.php'; ?>
<div id="main">
<div id='preview'>
<?php
$recipeid = isset($_POST['recipeid']) ? htmlspecialchars($_POST['recipeid']) : '';
$poster = isset($_POST['poster']) ? $_POST['poster'] : ''; // No need to sanitize
$comment = isset($_POST['comment']) ? htmlspecialchars($_POST['comment']) : '';
$date = date("Y-m-d");

// Prepare the SQL query using placeholders
$query = "INSERT INTO comments (recipeid, poster, date, comment) " .
          " VALUES (?, ?, ?, ?)";

// Prepare the statement
$stmt = $pdo->prepare($query);

// Bind parameters
$stmt->bindParam(1, $recipeid, PDO::PARAM_INT);
$stmt->bindParam(2, $poster, PDO::PARAM_STR);
$stmt->bindParam(3, $date, PDO::PARAM_STR);
$stmt->bindParam(4, $comment, PDO::PARAM_STR);

// Execute the statement
$result = $stmt->execute();

if ($result)
   echo "<h2>Comment posted</h2>\n";
else
   echo "<h2>Sorry, there was a problem posting your comment</h2>\n";

   echo "<a href=\"index.php?content=showrecipe&id=$recipeid\">Return to recipe</a>\n";
?>
</div>
</div>
