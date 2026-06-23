<?php
session_start();
?>
<!DOCTYPE>
<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css" />
<link rel="stylesheet" media="print" type="text/css" href="print.css" />
<link rel="icon" type="image/ico" href="images/favicon.ico">
<link href='http://fonts.googleapis.com/css?family=Quattrocento+Sans' rel='stylesheet' type='text/css'>
<title>ReciPHP v1</title>
</head>

<body>
<?php include("header.inc.php"); ?>
<div class="wrapper">

<?php include("nav.inc.php"); ?>
<!-- In the fixed code, I added a whitelist of allowed content files ( $allowedContentFiles ) and checked if
the requested content is in this whitelist before including the corresponding file. This prevents arbitrary
file inclusion and limits the included files to those explicitly allowed. If the requested content is not in the
whitelist, it defaults to including the main.inc.php file. -->
    <?php
    // Whitelist of allowed content files
    $allowedContentFiles = array(
        'main', 'login', 'register', 'showrecipe', 'addcomment', 'addrecipe', 'validate', 'news', 'print', 'search'
    );

    // Get the requested content
    $content = isset($_GET['content']) ? $_GET['content'] : 'main';
    
    // Validate the requested content
    if (in_array($content, $allowedContentFiles)) {
        $nextpage = $content . ".inc.php";
        // file deepcode ignore FileInclusion: Whitelist Approach $allowedContentFiles / Validation if its not in the white list we default to main.inc.php static file inclusion the list is hard coded 
        include($nextpage);
    } else {
        // If the requested content is not allowed, include the main page
        include("main.inc.php");
    }
    ?>

<?php include("news.inc.php"); ?>
</div>
<?php include("footer.inc.php"); ?>
</body>
</html>
