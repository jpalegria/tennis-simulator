<?php

namespace App\Utils;

trait Base2Util
{
    /**
     * Check if an array has a lenght in base 2.
     * @param array $data
     * @return bool|int
     */
    protected function isBase2(array $data)
    {
        $potence = log(count($data), 2);
        return preg_match('/^\d+$/', $potence);
    }
}
