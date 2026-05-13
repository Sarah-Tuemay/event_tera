<?php
// [WEEK 7: APIs & External Data Handling (RESTful concept, JSON)]
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *'); // Allows frontend JS to fetch this

require_once '../classes/Event.php';

 $eventObj = new Event();
// Output database data as pure JSON
echo $eventObj->getEventsJSON();
?>