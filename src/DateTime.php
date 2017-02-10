<?php
namespace System;

class DateTime
{
    private $_dateTime = 0;

    private $_timeZone;

    /**
    * 1ms=1000ns
    */
    private static $MilliSecond = 1000;

    /**
    * 1s=1000000ns
    */
    private static $Second = 1000000;

    /**
    * 1minute=60000000ns
    */
    private static $Minute = 60000000;

    /**
    * 1hour=3600000000ns
    */
    private static $Hour = 3600000000;

    /**
    * 1day=86400000000ns
    */
    private static $Day = 86400000000;

    /**
    * no leap year is 365 day
    */
    private static $NoLeapYear = 365;

    /**
    * leap year is 366 day;
    */
    private static $LeapYear = 366;

    /**
    * 4 year is 1461 day
    */
    private static $Days4Year = 1461;

    /**
    * 100 year is 36524 day
    */
    private static $Days100Year = 36524;

    /**
    * 400 year is 146097 day
    */
    private static $Days400Year = 146097;

    private static $DaysToMonth365 = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334, 365];
    private static $DaysToMonth366 = [0, 31, 60, 91, 121, 152, 182, 213, 244, 274, 305, 335, 366];

    /**
    * from 1-1-1 to 1970-1-1 is 62135596800000000ns
    */
    private static $Year1970 = 62135596800000000;

    /**
    * 0001-01-01 00:00:00 is 0ns
    */
    private static $MinValue = 0;

    /**
    * 10000-01-01 00:00:00 minus 1ns is 315537897599999999ns
    */
    private static $MaxValue = 315537897599999999;

    /**
    * DateTime part key Array
    */
    private static $DatePartArr=['Year','Month','Day','Hour','Minute','Second','Millisecond','Microsecond'];

    /**************************static function****************************/

    
    public static function MinValue()
    {
        $date = new DateTime();
        $date->_dateTime = self::$MinValue;
        return $date;
    }

    public static function MaxValue()
    {
        $date = new DateTime();
        $date->_dateTime = self::$MaxValue;
        return $date;
    }

    /**
     * Get the local DateTime
     *
     * @return  DateTime
     */
    public static function Now()
    {
        $date = new DateTime();
        $date->_dateTime = self::getUtcNow()+$date->_timeZone->offset*self::$Second;        
        return $date;
    }

    /**
     * Get the utc DateTime
     *
     * @return  DateTime
     */
    public static function UtcNow()
    {
        $date = new DateTime();
        $date->_dateTime = self::getUtcNow();
        $date->_timeZone=new TimeZone('utc');
        return $date;
    }

    /**
    * Returns an indication whether the specified year is a leap year.
    * 
    * @param  int $year
    *
    * @return bool
    */
    public static function IsLeapYear($year)
    {
        self::checkYear($year);
        return $year % 4 == 0 && ($year % 100 != 0 || $year % 400 == 0);
    }

    /**
    *
    * @param DateTime $dt1
    * @param DateTime $dt2
    *
    * @return int [-1,0,1]
    */
    public static function Compare($dt1,$dt2)
    {
        $diff=$dt1->Timestamp - $dt2->Timestamp;
        return $diff=0? 0:($diff>0? 1:-1);
    }

    /**
    * Returns the number of days in the specified month and year.
    *
    * @return int
    */
    public static function DaysInMonth($year,$month)
    {
        return self::getDaysByMonth($year,$month);
    }

    /*********************************************************************/

    /**
     * Create a new DateTime instance
     *
     * @param int [1,9999]      $year
     * @param int [1,12]        $month
     * @param int [1,31]        $day
     * @param int [0,23]        $hour
     * @param int [0,59]        $minute
     * @param int [0,59]        $second
     * @param int [0,999]       $millisecond
     * @param int [0,999]       $microsecond
     *
     * @return DateTime 
     * @see homepage https://guodf.github.com/PHP_DateTime
     */
    public function __construct($year = 1, $month = 1, $day = 1, $hour = 0, $minute = 0, $second = 0, $millisecond = 0,$microsecond=0)
    {
        $this->_dateTime = $this->getDateNS($year,$month,$day)+$this->getTimeNS($hour,$minute,$second,$microsecond,$microsecond);
        $this->_timeZone=new TimeZone();
    }

    /**
     * Gets the property value
     *
     * @param  string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->getDateTimePart($name);
    }

    /**
     * Gets the property value
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        switch ($name){
            case 'Timestamp':
                $this->_dateTime=$value;
                break;
            default:
                break;
        }
    }

    /**
     * Formater to string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getDateTimePart('Year') . '-' . $this->getDateTimePart('Month') . '-' . $this->getDateTimePart('Day')
        . ' ' . $this->getDateTimePart('Hour') . ':' . $this->getDateTimePart('Minute') . ':' . $this->getDateTimePart('Second').'.'.$this->getDateTimePart('Millisecond').' '.$this->getDateTimePart('Microsecond');
    }

    /*****************************helper private function**************************************/

    /**
     * Check whether a given year is correct
     *
     * @param  int $year
     */
    private static function checkYear($year)
    {
        if ($year <1 || $year > 9999){
            throw new DateTimeException('必须在[1,9999]年之间', 0, null);
        }
    }

    /**
     * Check whether a given month is correct
     *
     * @param  int $month
     */
    private static function checkMonth($month)
    {
        if ($month < 1 || $month > 12) {
            throw new DateTimeException('必须在[1,12]月之间', 0, null);
        }
    }

    /**
     * Check whether a given day is correct
     *
     * @param  int $year
     * @param  int $month
     * @param  int $day
     */
    private function checkDay($year, $month, $day)
    {
        $days=self::getDaysByMonth($year,$month);
        if ($day < 1 || $day > $days) {
            throw new DateTimeException("必须在[1,$days]之间", 0, null);
        }
    }

    /**
     * Check whether a given hour is correct
     *
     * @param  int $hour
     */
    private function checkHour($hour)
    {
        if ($hour < 0 && $hour > 23) {
           throw new DateTimeException('必须在[0,23]之间', 0, null);
        }
    }

    /**
     * Check whether a given minute is correct
     *
     * @param  int $minute
     */
    private function checkMinute($minute)
    {
        if ($minute < 0 || $minute > 59) {
            throw new DateTimeException('必须在[0,59]之间', 0, null);
        }
    }

    /**
     * Check whether a given second is correct
     *
     * @param  int $second
     */
    private function checkSecond($second)
    {
        if ($second < 0 || $second > 59) {
            throw new DateTimeException('必须在[0,59]之间', 0, null);
        }
    }

    /**
     * Check whether a given millisecond is correct
     *
     * @param  int $millisecond
     */
    private function checMillisecond($millisecond)
    {
        if ($millisecond < 0 || $millisecond > 999) {
            throw new DateTimeException('必须在[0,999]之间', 0, null);
        }
    }

    /**
     * Check whether a given microsecond is correct
     *
     * @param  int $microsecond
     */
    private function checMicrosecond($microsecond)
    {
        if ($microsecond <0 || $microsecond > 999) {
            throw new DateTimeException('必须在[0,999]之间', 0, null);
        }
    }

    /**
     * Get DateTime part number
     *
     * @param  string $part
     * @return int
     */
    private function getDateTimePart($part)
    {
        switch ($part) {
            case 'Timestamp':
                return $this->_dateTime;
            case 'Year':
            case 'Month':
            case 'Day':
            case 'DayOfWeek':
            case 'DayOfYear':
                return $this->getDatePart($part);
            case 'Hour':
            case 'Minute':
            case 'Second':
            case 'Millisecond':
            case 'Microsecond':
                return $this->getTimePart($part);
            default:
                throw new DateTimeException("不存在的属性调用", 1, null);
                break;
        }
    }

    /**
     * Get date part number
     *
     * @param  string $part
     * @return int
     */
    private function getDatePart($part)
    {
        //get total days
        $days = intval(($this->_dateTime-($this->_dateTime%self::$Day)) / self::$Day);
        if ($part == 'DayOfWeek') {
            return $days % 7 + 1;
        }
        $n400Year = intval($days / self::$Days400Year);
        $days -= $n400Year * self::$Days400Year;
        $n100Year = intval($days / self::$Days100Year);
        $days -= $n100Year * self::$Days100Year;
        $n4Year = intval($days / self::$Days4Year);
        $days -= $n4Year * self::$Days4Year;
        $nYear = intval($days / self::$NoLeapYear);

        //year
        if ($part == 'Year') {
            return $n400Year * 400 + $n100Year * 100 + $n4Year * 4 + $nYear + 1;
        }
        //month
        $dayArr = $nYear + 1 == 4 && $n4Year != 0 ? self::$DaysToMonth366 : self::$DaysToMonth365;
        $days -= $nYear * self::$NoLeapYear;
        if ($part == 'DayOfYear') {
            return $days + 1;
        }
        $month = 0;
        while ($days >= $dayArr[$month]) {
            $month++;
        }
        if ($part == 'Month') {
            return $month;
        }
        //day
        return $days - $dayArr[$month - 1] + 1;
    }

    /**
     * Get time part number
     *
     * @param  string $part
     * @return int
     */
    public function getTimePart($part)
    {
        $time=$this->_dateTime%self::$Day;
        if ($part == 'Hour') {
            return intval($time / self::$Hour) % 24;
        }
        if ($part == 'Minute') {
            return intval($time / self::$Minute) % 60;
        }
        if ($part == 'Second') {
            return intval($time / self::$Second) % 60;
        }
        if ($part == 'Millisecond') {
            return intval($time / self::$MilliSecond) % 1000;
        }
        if ($part == 'Microsecond') {
            return $time % 1000;
        }
    }

    /**
     * Get utc time
     */
    private static function getUtcNow()
    {
        list($usec, $sec) = explode(" ", microtime());
        return self::$Year1970 + $sec * self::$Second + (int) ($usec * self::$Second);
    }

    /**
     * Gets the month number of days
     *
     * @param  int $year
     * @param  int $month
     * @return int
     */
    private static function getDaysByMonth($year, $month)
    {
        self::checkYear($year);
        self::checkMonth($month);

        $days = self::isLeapYear($year) ? self::$DaysToMonth366 : self::$DaysToMonth365;
        return $days[$month] - $days[$month - 1];
    }

    /**
    * Get date part number of microseconds
    *
    * @param  int $year
    * @param  int $month
    * @param  int $day
    * @return int
    */
    private function getDateNS($year,$month,$day)
    {
        self::checkYear($year);
        $this->checkMonth($month);
        $this->checkDay($year,$month,$day);

        $overYear=$year-1;
         //已经过去得年数
        $overYear = $year - 1;
        $days = $overYear * self::$NoLeapYear + intval($overYear / 4) - intval($overYear / 100) + intval($overYear / 400);
        //计算此年已过去的月数对应的天数
        $days += self::isLeapYear($year)? self::$DaysToMonth366[$month - 1]:self::$DaysToMonth365[$month - 1];
        //计算此年此月已过去的天数
        $days += $day - 1;
        return $days*self::$Day;
    }


   /**
    * Get time part number of microseconds
    * @param  int $hour
    * @param  int $minute
    * @param  int $second
    * @param  int $millisecond
    * @param  int $microsecond
    * @return int
    */
    private function getTimeNS($hour, $minute, $second , $millisecond ,$microsecond)
    {
        $this->checkHour($hour);
        $this->checkMinute($minute);
        $this->checkSecond($second);
        $this->checMillisecond($microsecond);
        $this->checMicrosecond($microsecond);

        return $hour*self::$Hour+$minute*self::$Minute+$second*self::$Second+$millisecond*self::$MilliSecond+$microsecond;
    }

    /****************************************************************************/

    /****************************add DateTime part************************************/

    /**
     * Increase the number of specified years
     *
     * @param  DateTime $dateTime
     * @return DateTime  A new instance
     */
    public function addDateTime($dateTime)
    {
        $this->Timestamp+=$dateTime->Timestamp;
        return $this;
    }

    /**
     * Increase the number of specified years
     *
     * @param  int $years
     * @return DateTime  A new instance
     */
    public function addYear($years)
    {
        if ($years < -10000 || $years > 10000) {
            throw new DateTimeException('必须在(-10000,10000]之间', 0, null);
        }
        return $this->addMonths($years * 12);
    }

    /**
     * Increase the number of specified months
     *
     * @param  int $months
     * @return DateTime  A new instance
     */
    public function addMonths($months)
    {
        if ($months < -120000 || $months > 120000) {
            throw new DateTimeException('必须在(-12000,12000)之间', 0, null);
        }
        $year = $this->getDatePart('Year');
        $month = $this->getDatePart('Month');
        $day = $this->getDatePart('Day');
        //实际上过完了$months-1个月
        $months += $month - 1;
        if ($months > 0) {
            $year += intval($months / 12);
            $month = $months % 12 + 1;
        } else {
            $year += intval(($months - 11) / 12);
            $month = ($months + 1) % 12 + 12;
        }
        if ($year < 1 || $year > 9999) {
            throw new DateTimeException('必须在[1,999]之间', 0, null);
        }
        $nDays = $this->getDaysByMonth($year, $month);
        $day = $day > $nDays ? $nDays : $day;

        //get time
        $time = $this->_dateTime % self::$Day;

        $this->_dateTime=$this->getDateNS($year,$month,$day)+$time;
        return $this;
    }

    /**
     * Increase the number of specified days
     *
     * @param  int $days
     * @return DateTime  A new instance
     */
    public function addDays($days)
    {
        return $this->addMicroseconds($days * self::$Day);
    }

    /**
     * Increase the number of specified hours
     *
     * @param  int $hours
     * @return DateTime  A new instance
     */
    public function addHours($hours)
    {
        return $this->addMicroseconds($hours * self::$Hour);
    }

    /**
     * Increase the number of specified minutes
     *
     * @param  int $minutes
     * @return DateTime  A new instance
     */
    public function addMinutes($minutes)
    {
        return $this->addMicroseconds($minutes * self::$Minute);
    }

    /**
     * Increase the number of specified seconds
     *
     * @param  int $seconds
     * @return DateTime  A new instance
     */
    public function addSeconds($seconds)
    {
        return $this->addMicroseconds($seconds * self::$Second);
    }

    /**
     * Increase the number of specified milliseconds
     *
     * @param  int $milliseconds
     * @return DateTime  A new instance
     */
    public function addMilliseconds($milliseconds)
    {
        return $this->addMicroseconds($milliseconds * self::$MilliSecond);
    }

    /**
     * Increase the number of specified microseconds
     *
     * @param  int $microseconds
     * @return DateTime  A new instance
     */
    public function addMicroseconds($microseconds)
    {
        $this->_dateTime += $microseconds;
        if ($this->_dateTime < self::$MinValue || $this->_dateTime > self::$MaxValue) {
            throw new DateTimeException('超出DateTime支持范围', 0, null);
        }
        return $this;
    }
    
    /*******************************************************************************/

    /**
    * Change DateTime TimeZone
    * 
    * @param TimeZone $tz
    *
    * @return DateTime
    */
    public function changeTimeZone($tz)
    {
        $this->_dateTime+=($tz->offset-$this->_timeZone->offset)*self::$Second;
        $this->_timeZone=$tz;
        return $this;
    }
    
}
