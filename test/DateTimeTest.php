<?php
require_once __DIR__ .'./../vendor/autoload.php';

use System\DateTime;
use System\DateTimeException;
use System\TimeZone;

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
    var_dump(DateTime::IsLeapYear(2000));
    echo DateTime::DaysInMonth(2017,2);

    //DateTime::Compamer($dt1,$dt2);
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

    //DateTime get 操作
    echo $time->Year;
    echo $time->Month;
    echo $time->Day;
    echo $time->DayOfWeek;
    echo $time->DayOfYear;
    echo $time->Hour;
    echo $time->Minute;
    echo $time->Second;
    echo $time->Millisecond;
    echo $time->Microsecond;
    echo $time->Timestamp;
    $date=DateTime::Now();
    echo $date->Date;
    echo $date->Date->addDays(1);

    //DateTime 比较大小
    var_dump(DateTime::Now()->Timestamp >= DateTime::Now()->Timestamp);
    echo DateTime::Compare(DateTime::Now(),DateTime::Now());

    //改变时区
    writeLine(DateTime::Now()->ChangeTimeZone(new TimeZone('utc')));

    //格式化时间字符串
    //echo DateTime::Format(DateTime::Now());
    
    // //DateTime set 操作
    // $time->setYear(1);
    // $time->setMonth(1);
    // $time->setDay(1);
    // $time->setHour(1);
    // $time->setMinute(1);
    // $time->setSecond(1);
    // $time->setMillisecond(10);
    // $time->setMicrosecond(10);

}
catch(DateTimeException $e)
{
    echo $e->message."\n";
}