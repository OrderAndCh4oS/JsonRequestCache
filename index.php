<?php
    require('EventBrite.php');
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
$eb = new EventBrite('https://www.eventbriteapi.com/v3/users/me/owned_events/');

foreach ($eb->getEvents()->events as $event) {
    echo "<h3>". $event->name->html . "</h3>";
}
?>
</body>
</html>
