<?php
include __DIR__ . '/../src/DateTime.php';
use System\DateTime;
try {
    //create DateTime instance
    echo new DateTime();
    echo new DateTime(1956);
    echo new DateTime(1956,1,1);
    echo new DateTime(1956,1,1,14);
    echo new DateTime(1956,2,1,23,34,12,150);
    echo new DateTime(1956,2,1,23,34,12,230,333);
    echo DateTime::MinValue();
    echo DateTime::MaxValue();
    echo DateTime::Now();
    echo DateTime::UtcNow();
    echo DateTime::IsLeapYear(1923);
    echo DateTime::DaysInMonth(1923,2);
    echo DateTime::Compare(new DateTime(),new DateTime());

    $time=new DateTime();
    //add operation
    $time->addDateTime(new DateTime(1,1,1));
    $time->addYear(1); //$time->addYear(-1);
    $time->addMonths(1);
    $time->addDays(1);
    $time->addHours(1);
    $time->addMinutes(1);
    $time->addSeconds(1);
    $time->addMilliseconds(10);
    $time->addMicroseconds(10);
    
    //set operation
    $time->setYear(1);
    $time->setMonth(1);
    $time->setDay(1);
    $time->setHour(1);
    $time->setMinute(1);
    $time->setSecond(1);
    $time->setMillisecond(10);
    $time->setMicrosecond(10);

    //get operation
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

    //compare size
    echo DateTime::Now()->Size >= DateTime::Now()->Size;

    //时区转换(自定义时区类)
    echo $time->toTimeZone(new TimeZone('shanghai'));

    //格式化显示(自定义格式化函数)
    echo $time->formatToString(function(){});

    //判断操作
    //1.闰年
    //2.年的天数
    //3.月的天数
} catch (DateTimeException $e) {
    echo $e;
}
