<?php
namespace Tnt;

/**
 * Class Adder
 * @package Tnt
 */
class Adder
{
    /**
     * @param int $first
     * @param int $second
     * @return int
     */
    public function add($first, $second)
    {
        return (int) $first + (int) $second;
    }
}