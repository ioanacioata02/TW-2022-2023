<?php

//creditds to https://stackoverflow.com/questions/5631387/php-array-to-postgres-array


class Utils
{

    public static function toPostgresArray($set)
    {
        settype($set, 'array'); // can be called with a scalar or array
        $result = array();
        foreach ($set as $t) {
            if (is_array($t)) {
                $result[] = to_pg_array($t);
            } else {
                $t = str_replace('"', '\\"', $t); // escape double quote
                if (!is_numeric($t)) // quote only non-numeric values
                    $t = '"' . $t . '"';
                $result[] = $t;
            }
        }

        return  "'" .'{' . implode(",", $result) . '}'. "'" ; // format

    }

}