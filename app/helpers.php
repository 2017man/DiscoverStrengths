<?php

/**
 * 从$input数组中取$m个数的组合算法
 */
if (!function_exists('comb')) {
    function comb($input, $m)
    {
        if ($m == 1) {
            foreach ($input as $item) {
                $result[] = array($item);
            }
            return $result;
        }
        for ($i = 0; $i <= count($input) - $m; $i++) {
            $nextinput  = array_slice($input, $i + 1);
            $nextresult = comb($nextinput, $m - 1);
            foreach ($nextresult as $one) {
                $result[] = array_merge(array($input[$i]), $one);
            }
        }
        return $result;
    }
}
/**
 * 从$input数组中取$m个数的排列算法
 */
if (!function_exists('perm')) {
    function perm($input, $m)
    {
        if ($m == 1) {
            foreach ($input as $item) {
                $result[] = array($item);
            }
            return $result;
        }
        for ($i = 0; $i < count($input); $i++) {
            $nextinput  = array_merge(array_slice($input, 0, $i), array_slice($input, $i + 1));
            $nextresult = perm($nextinput, $m - 1);
            foreach ($nextresult as $one) {
                $result[] = array_merge(array($input[$i]), $one);
            }
        }
        return $result;
    }
}

/**
 * 隐藏电话号码
 */
if (!function_exists('encrypTel')) {
    function encrypTel($phoneNumber)
    {
        $hidden_number = substr($phoneNumber, 0, 3) . "****" . substr($phoneNumber, 7);
        return $hidden_number;
    }
}

/**
 * 隐藏除字符串首字母以外的字符
 */
if (!function_exists('encrypFirstStr')) {
    function encrypFirstStr($str)
    {;
        $replaceStr = mb_substr($str, 0, 1) . implode('', array_fill(0, mb_strlen($str) - 1, '*'));
        return $replaceStr;
    }
}
//    生成随机字母 + 2位数字
if (!function_exists('str_random')) {
    function str_random($length = 4)
    {
        $str    = '';
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $max    = strlen($strPol) - 1;
        // 生成2位数字
        $num = rand(10, 99);
        for ($i = 0; $i < $length; $i++) {
            $str .= $strPol[rand(0, $max)]; // 生成随机字母
        }
        return $str . $num;
    }
}
