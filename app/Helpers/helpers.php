<?php

function valid_latlong($lat, $long)
{
    if (!is_numeric($lat) || !is_numeric($long)) {
        return false;
    }
    $lat = floatval($lat);
    $long = floatval($long);
    return ($lat >= -90 && $lat <= 90) && ($long >= -180 && $long <= 180);
}
