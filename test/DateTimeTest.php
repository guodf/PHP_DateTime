<?php
include __DIR__ . '/../src/DateTime.php';

use System\DateTime;
use System\DateTimeException;

function writeLine($dt)
{
    echo $dt."\n";
}
try
{
    //创建DateTime实例
    $dt1=new DateTime();
    writeLine($dt1);
    $dt2=new DateTime(1,1,1);
    writeLine($dt2);
    $dt3=new DateTime(1,1,1,1,1,1,1,1);
    writeLine($dt3);

    //DateTime 静态方法
    $min=DateTime::MinValue();
    writeLine($min);
    $max=DateTime::MaxValue();
    writeLine($max);
    $now=DateTime::Now();
    writeLine($now);
    $utcNow=DateTime::UtcNow();
    writeLine($utcNow);

    //DateTime add 操作
    $time=new DateTime();
    $time->addDateTime(new DateTime(1,1,1));
    $time->addYear(4); 
    $time->addYear(-1);
    $time->addMonths(1);
    $time->addDays(1);
    $time->addHours(1);
    $time->addMinutes(1);
    $time->addSeconds(1);
    $time->addMilliseconds(10);
    $time->addMicroseconds(10);
    writeLine($time);
    
}
catch(DateTimeException $e)
{
    echo $e->message."\n";
}