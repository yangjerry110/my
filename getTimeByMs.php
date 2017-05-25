/** 获取当前时间戳，精确到毫秒 */
    public static function microtime_float()
    {
       list($usec, $sec) = explode(" ", microtime());
       return ((float)$usec + (float)$sec);
    }

    /** 格式化时间戳，精确到毫秒，x代表毫秒 */
    public static function microtime_format($tag, $time)
    {
        //echo $time;exit;
       list($usec, $sec) = explode(".", substr($time,0,-1));
       //$sec = substr($sec,0,-1);
       //echo $sec;exit;
       $date = date($tag,$usec);
       //echo $date;exit;
       return str_replace('x', $sec, $date);
    }
