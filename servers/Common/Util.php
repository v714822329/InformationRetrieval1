<?php

class Util
{
    public static function isUtf8($str)
    {
		$e = mb_detect_encoding($str, array('UTF-8', 'GBK'));
		return ($e == 'UTF-8');
	}
	
    public static function output(&$response, $callback = null)
    {
        ob_start();
        
        $jsonResp = json_encode($response);
        if ($response["code"] == OPCode::kSuccess)
        {
            $now = Util::getServerTime();
            
            $appKey = CSVServer::getInstance()->getAppKey();
            $signStr = $appKey . $jsonResp . GameState::getAccountUUID();
            $sign = md5($signStr);
            
            //logDebug("signStr=".$signStr."  sign=".$sign);
            $jsonResp = substr($jsonResp, 0, strlen($jsonResp) - 1);
            $jsonResp = $jsonResp . ',"sign":"' . $sign . '"}';
        }

        if($callback){
            echo $callback.'('.$jsonResp.')';
        } else {
            echo $jsonResp;
        }

        ob_end_flush();
    }

    public static function str_hexsum($str)
    {
        $sum = 0;
        $len = strlen($str);

        for ($i = 0; $i < $len; $i++)
        {
            $ch = substr($str, $i, 1);
            $sum += hexdec($ch);
        }

        return $sum;
    }

    public static function emptyUUID()
    {
        return "00000000000000000000000000000000";
    }

    public static function getServerTime()
    {
        return time();
    }

    public static function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        
        return ((float) $usec + (float) $sec);
    }
    
    public static function isSameDay($left, $right)
    {
        $leftDateTime  = new DateTime();
        $rightDateTime = new DateTime();

        $leftDateTime->setTimestamp($left);
        $rightDateTime->setTimestamp($right);

        $leftDate  = $leftDateTime->format("Y-m-d");
        $rightDate = $rightDateTime->format("Y-m-d");

        if (strcmp($leftDate, $rightDate) == 0)
        {
            return true;
        }

        return false;
    }
    
    // 两个日期的间隔时间
    public static function twoDateInterval($day1, $day2)
    {
        $tm1 = strtotime($day1);
        $tm2 = strtotime($day2);
        return abs((int)(($tm2 - $tm1) / (24 * 60 * 60)));
    }

    public static function convertUnixTimeFromString($str)
    {
        $year = (int) substr($str, 0, 4);
        $mon  = (int) substr($str, 4, 2);
        $day  = (int) substr($str, 6, 2);
        $hour = (int) substr($str, 8, 2);
        $min  = (int) substr($str, 10, 2);
        $sec  = (int) substr($str, 12, 2);
         
        $dt = new DateTime();
        $dt->setDate($year, $mon, $day);
        $dt->setTime($hour, $min, $sec);
        
        $value = $dt->getTimestamp();
        
        return $value;
    }
    
    public static function hashHmac_SHA1($str, $key)
    {
        $sign = "";
        if (function_exists('hash_hmac'))
        {
            $sign = hash_hmac("sha1", $str, $key);
        }
        else
        {
            $blockSize = 64;
            $hashFunc = 'sha1';
            if (strlen($key) >= $blockSize)
            {
                $key = pack('H*', $hashFunc($key));
            }
            $key  = str_pad($key, $blockSize, chr(0x00));
            $ipad = str_repeat(chr(0x36), $blockSize);
            $opad = str_repeat(chr(0x5c), $blockSize);
            $hmac = pack('H*', $hashFunc(($key^$opad).pack('H*', $hashFunc(($key^$ipad).$str))));
            $sign = base64_encode($hmac);
        }
        return $sign;
    }
}

?>
