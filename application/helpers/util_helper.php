<?php

/**
 * Util Helpers
 *
 * @author Leoanrdo Quintero 
 * @author Carlos Bello
 */
// ------------------------------------------------------------------------

/**
 * db_to_Local
 *
 * Converte una fecha en formato BD MySQL a formato normal
 *
 * @return  string.
 */
if (!function_exists('db_to_Local')) {

    function db_to_Local($dateDB = '') {
        if (($dateDB == "") || ($dateDB == "0000-00-00"))
            return '';

        $dateArray = explode("-", $dateDB);
        $aux = $dateArray[2];
        $dateArray[2] = $dateArray[0];
        $dateArray[0] = $aux;
        return implode("/", $dateArray);
    }

}
?>