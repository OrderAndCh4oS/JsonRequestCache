<?php
require( 'ReadJson.php' );
?>
<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Curl Test</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
<?php
$page = !empty($_GET['page']) ? (int) $_GET['page'] : 1;
$args = array(
    'per_page' => 20,
    'page' => $page
);
$eb = new ReadJson('https://www.eventbriteapi.com/v3/users/me/owned_events/?status=live,started', $args);
if ($eb->getItems()) {
    echo $eb->pagination();
    foreach ($eb->getItems() as $event) {
        echo "<h3>".$event->name->html."</h3>";
        echo "<p>Places: ".$event->capacity."</p>";
        echo "<p>Date: ".date("l, j F, Y", strtotime($event->start->local))."</p>";
        echo "<p>Starts: ".date("H:i", strtotime($event->start->local))."</p>";
        echo "<p>Finishes: ".date("H:i", strtotime($event->end->local))."</p>";
        echo "<a href=\"".$event->url."\">Book Now</a>";
    }
}
?>
</body>
</html>
