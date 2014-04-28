<?php

if (!function_exists('pickMiddlePartAndDecorateWithDots')) {
    function pickMiddlePartAndDecorateWithDots(
        &$text,
        $length,
        $fromThisPart,
        $toThisPart
    ) {
        if (empty(trim($text))) {
            return false;
        }

        $text = trim($text);
        $textLenght = mb_strlen($text);

        $start = mb_strpos($text, $fromThisPart);
        $end = mb_strrpos($text, $toThisPart);

        $pickedLength = ($end - $start);
        if ($pickedLength > $length) {
            $end = mb_strpos($text, $toThisPart);
        }

        $pickStartPosition = round($start-(($length-$pickedLength)/2));
        $pickEndPosition = round($end+(($length-$pickedLength)/2));

        $prefix = $sufix = '..';
        if ($pickStartPosition < 0) {
            $pickStartPosition = 0;
            $pickEndPosition = $length;
            $prefix = '';
        }

        if ($pickEndPosition >= $textLenght) {
            $pickEndPosition = $textLenght;
            $pickStartPosition = max($pickEndPosition - $length, 0);
            $sufix = '';
        }

        $pickedPart = mb_substr($text, $pickStartPosition, $length);
        $text = implode('', [$prefix, $pickedPart, $sufix]);
    }
}
