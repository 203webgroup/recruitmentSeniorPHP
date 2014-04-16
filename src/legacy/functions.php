<?php

if (!function_exists('whatTheFuckDoesThisFunction')) {
    function whatTheFuckDoesThisFunction(&$text, $length, $starting, $ending) {
        $strlen = mb_strlen(trim($text));

        if($strlen <= 0) {
            return false;
        }

        //$length = 6;

        $startdots = $enddots = '..';

        $start = mb_strpos($text, $starting);
        $end = mb_strrpos($text, $ending);

        if($start === false) {
            if($strlen > $length) {
                mb_substr($text, 0, $length).$enddots;
            }
        }

        if (($end-$start) > $length) {
            $end = mb_strpos($text, $ending);

            $diff = ($end-$start);

            $starter = round($start-(($length-$diff)/2));
            $ender = round($end+(($length-$diff)/2));
        } else {
            $diff = ($end-$start);

            $starter = round($start-(($length-$diff)/2));
            $ender = round($end+(($length-$diff)/2));
        }
        if($starter < 0) {
            $starter = 0;
            $ender = $length;
            $startdots = '';
        }

        if($ender >= $strlen) {
            $ender = $strlen;
            $starter = $ender-$length;
            $enddots = '';
        }

        if($starter < 0) {
            $starter = 0;
        }

        $text = $startdots.mb_substr($text, $starter, $length).$enddots;
    }
}

