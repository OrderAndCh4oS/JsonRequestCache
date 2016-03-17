<?php
require( 'ReadJson.php' );

function printCourses($args) {
    $eb = new ReadJson(urlencode('https://www.eventbriteapi.com/v3/users/me/owned_events/?expand=category&status=live,started'), $args);
    if ($eb->getItems()) {
        echo $eb->pagination();
        foreach ($eb->getItems() as $event) {
            echo "<div class=\"events\">";
            echo "<h3>".$event->name->html."</h3>";
            echo "<p>Places: ".$event->capacity."</p>";
            echo "<p>Date: ".date("l, j F, Y", strtotime($event->start->local))."</p>";
            echo "<p>Starts: ".date("H:i", strtotime($event->start->local))."</p>";
            echo "<p>Finishes: ".date("H:i", strtotime($event->end->local))."</p>";
            echo "<p>Category: ".$event->category->name."</p>";
            echo "<a href=\"".$event->url."\">Book Now</a>";
            echo "</div>";
        }
    } else {
        echo "<div class=\"events\">";
        echo "<h3>Please contact us for more information about the courses we have available</h3>";
        echo "</div>";
    }
}

$page = !empty($_GET['page']) ? (int) $_GET['page'] : 1;
$args = array(
    'per_page' => 20,
    'page' => $page
);

printCourses($args);