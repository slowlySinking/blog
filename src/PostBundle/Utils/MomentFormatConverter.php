<?php

namespace PostBundle\Utils;

class MomentFormatConverter
{
    /**
     * @var array
     */
    private static $formatConvertRules = [
        'yyyy' => 'YYYY', 'yy' => 'YY', 'y' => 'YYYY',
        'dd' => 'DD', 'd' => 'D',
        'EE' => 'ddd', 'EEEEEE' => 'dd',
        'ZZZZZ' => 'Z', 'ZZZ' => 'ZZ',
        '\'T\'' => 'T',
    ];

    /**
     * @param $format
     * @return string
     */
    public function convertFormat($format)
    {
        return strtr($format, self::$formatConvertRules);
    }
}