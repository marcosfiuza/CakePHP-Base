<?php
App::uses('TimeHelper', 'View/Helper');

class CustomTimeHelper extends TimeHelper
{
    public function format($format, $date = null, $invalid = false, $timezone = null)
    {
        $customDate = $this->fromString($format, $timezone);
        
        if ($customDate && preg_match('(%B|%b)', $date))
        {
            $months = array(
                __d('cake', 'January'),
                __d('cake', 'February'),
                __d('cake', 'March'),
                __d('cake', 'April'),
                __d('cake', 'May'),
                __d('cake', 'June'),
                __d('cake', 'July'),
                __d('cake', 'August'),
                __d('cake', 'September'),
                __d('cake', 'October'),
                __d('cake', 'November'),
                __d('cake', 'December')
            );
            
            $month = $months[gmdate('n', $customDate) - 1];
            
            $date = str_replace('%B', $month, $date);
            $date = str_replace('%b', substr($month, 0, 3), $date);
        }
        
        return parent::format($format, $date, $invalid, $timezone);
    }
    
    public function weekday($weekday)
    {
        $weekdays = array(
            __d('cake', 'Sunday'),
            __d('cake', 'Monday'),
            __d('cake', 'Tuesday'),
            __d('cake', 'Wednesday'),
            __d('cake', 'Thursday'),
            __d('cake', 'Friday'),
            __d('cake', 'Saturday')
        );
        
        if ($weekday < sizeof($weekdays))
        {
            return $weekdays[$weekday];
        }
        
        return false;
    }
}