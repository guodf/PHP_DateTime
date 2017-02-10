<?php
include __DIR__ . '/../src/TimeZone.php';
use System\TimeZone;
$tz=new TimeZone();
echo $tz->getName().'   '.$tz->getOffset().'\n';
$tz1=new TimeZone('America/Anchorage');
echo $tz1->getName().'   '.$tz1->getOffset();