<?php

/**
 * Berechnet die Distanz zwischen zwei Koordinaten in km.
 * @param float $lat1 Breitengrad der ersten Koordinate
 * @param float $lng1 Längengrad der ersten Koordinate
 * @param float $lat2 Breitengrad der zweiten Koordinate
 * @param float $lng2 Längengrad der zweiten Koordinate
 * @return float Entfernung in km.
 */
function get_geo_distance($lat1, $lng1, $lat2, $lng2) {
    $pi80 = M_PI / 180;
    $lat1 *= $pi80;
    $lng1 *= $pi80;
    $lat2 *= $pi80;
    $lng2 *= $pi80;

    $r = 6372.797;
    $dlat = $lat2 - $lat1;
    $dlng = $lng2 - $lng1;
    $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlng / 2) * sin($dlng / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    return $r * $c;
}
?>

