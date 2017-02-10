<?php
require_once __DIR__ .'./../vendor/autoload.php';
use System\TimeZone;

$tz=new TimeZone();
echo $tz->getName().'   '.$tz->offset.'\n';
$tz1=new TimeZone('America/Anchorage');
echo $tz1->getName().'   '.$tz1->offset."\n";
$tz2=new TimeZone('utc');
echo $tz2->getName().'  '.$tz2->offset;