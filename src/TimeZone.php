<?php
namespace System;

class TimeZone extends \DateTimeZone
{
    public function __construct($tzName=null)
    {
        $tzName=empty($tzName)? date_default_timezone_get() :$tzName;
        parent::__construct($tzName);
    }

    /**
    *
    * Get TimeZone offset
    *
    * @return int seconds
    */
    public function getOffset()
    {
        return parent::getOffset(date_create('now'));
    }
}