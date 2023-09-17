<?php

namespace matpoppl\DbEntityGenerator\Formatter;

class CamelCase
{
    public function from($input)
    {
        $input = preg_replace('#([A-Z]+)#', ' $1', $input);
        $input = str_replace(' ', '_', $input);
        return strtolower($input);
    }

    public function to($input)
    {
        $input = preg_replace('#[[:punct:]]+#', ' ', $input);
        $input = ucwords($input);
        $input = str_replace(' ', '', $input);
        return lcfirst($input);
    }
}
