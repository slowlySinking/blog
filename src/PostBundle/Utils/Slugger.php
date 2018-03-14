<?php

namespace PostBundle\Utils;

class Slugger
{
    /**
     * @param $string
     * @return mixed
     */
    public function sluggify($string)
    {
        return preg_replace('/\s+/', '-', mb_strtolower(trim(strip_tags(str_replace('.', '', $string))), 'UTF-8'));
    }
}