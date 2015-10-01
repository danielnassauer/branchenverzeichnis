<?php

/**
 * Fügt einen neuen Unternehmenseintrag ein.
 * @param string $title Titel der Firma
 * @param string $content Beschreibung der Firma
 * @param string $type Gewerbe-Tags getrennt durch ","
 * @param string $plz PLZ
 * @param string $city Stadt
 * @param string $street Straße
 * @param string $housenr Haus-Nr.
 * @param string $telephone Telefon-Nr.
 * @param string $email EMail-Adresse
 * @param string $website URL der Webseite
 * @param string $longitude Längengrad
 * @param string $latitude Breitengrad
 * @param string $google_id Google-ID des angemeldeten Unternehmers
 */
function add_post($title, $content, $type, $plz, $city, $street, $housenr, $telephone, $email, $website, $longitude, $latitude, $google_id) {
    // neuen Post erzeugen
    $post_id = wp_insert_post(array(
        'post_type' => 'company',
        'post_title' => $title,
        'post_content' => $content,
        'post_status' => 'publish'
    ));
    // Custom-Fields setzen
    add_post_meta($post_id, 'bd_plz', $plz);
    add_post_meta($post_id, 'bd_city', $city);
    add_post_meta($post_id, 'bd_street', $street);
    add_post_meta($post_id, 'bd_housenr', $housenr);
    add_post_meta($post_id, 'bd_telephone', $telephone);
    add_post_meta($post_id, 'bd_email', $email);
    add_post_meta($post_id, 'bd_website', $website);
    add_post_meta($post_id, 'bd_longitude', $longitude);
    add_post_meta($post_id, 'bd_latitude', $latitude);
    add_post_meta($post_id, 'bd_google_id', $google_id);
    add_post_meta($post_id, 'bd_last_login', time());
    // Tags (Gewerbe) hinzufügen
    wp_set_object_terms($post_id, explode(",", $type), 'type');
}

/**
 * Ändert einen bestehenden Unternehmens-Eintrag ab.
 * @param string $content Beschreibung der Firma
 * @param string $plz PLZ
 * @param string $city Stadt
 * @param string $street Straße
 * @param string $housenr Haus-Nr.
 * @param string $telephone Telefon-Nr.
 * @param string $email EMail-Adresse
 * @param string $website URL der Webseite
 * @param string $longitude Längengrad
 * @param string $latitude Breitengrad
 * @param string $google_id Google-ID des angemeldeten Unternehmers
 */
function update_post($content, $plz, $city, $street, $housenr, $telephone, $email, $website, $longitude, $latitude, $user_id) {
    // entsprechenden Post suchen
    $post = get_post_by_google_id($user_id);
    // Custom-Fields updaten
    update_post_meta($post->ID, 'bd_plz', $plz);
    update_post_meta($post->ID, 'bd_city', $city);
    update_post_meta($post->ID, 'bd_street', $street);
    update_post_meta($post->ID, 'bd_housenr', $housenr);
    update_post_meta($post->ID, 'bd_telephone', $telephone);
    update_post_meta($post->ID, 'bd_email', $email);
    update_post_meta($post->ID, 'bd_website', $website);
    update_post_meta($post->ID, 'bd_longitude', $longitude);
    update_post_meta($post->ID, 'bd_latitude', $latitude);
    // Content updaten
    wp_update_post(array(
        'ID' => $post->ID,
        'post_content' => $content
    ));
}

/**
 * Löscht einen Unternehmens-Eintrag.
 * @param int $post_id ID des Posts
 */
function delete_post($post_id) {
    wp_delete_post($post_id, true);
}

/**
 * Liefert die PLZ eines Unternehmens-Eintrag.
 * @param int $post_id ID des Posts
 * @return string PLZ
 */
function get_post_plz($post_id) {
    return get_post_meta($post_id, 'bd_plz', FALSE)[0];
}

/**
 * Liefert die Stadt eines Unternehmens-Eintrag.
 * @param int $post_id ID des Posts
 * @return string Stadt
 */
function get_post_city($post_id) {
    return get_post_meta($post_id, 'bd_city', FALSE)[0];
}

/**
 * Liefert die Straße eines Unternehmens-Eintrag.
 * @param int $post_id ID des Posts
 * @return string Straße
 */
function get_post_street($post_id) {
    return get_post_meta($post_id, 'bd_street', FALSE)[0];
}

/**
 * Liefert die Haus-Nr. eines Unternehmens-Eintrag.
 * @param int $post_id ID des Posts
 * @return string Haus-Nr.
 */
function get_post_housenr($post_id) {
    return get_post_meta($post_id, 'bd_housenr', FALSE)[0];
}

/**
 * Liefert die Telefon-Nr. eines Unternehmens-Eintrag.
 * @param int $post_id ID des Posts
 * @return string Telefon-Nr.
 */
function get_post_telephone($post_id) {
    return get_post_meta($post_id, 'bd_telephone', FALSE)[0];
}

/**
 * Liefert die EMail-Adresse eines Unternehmens-Eintrag.
 * @param int $post_id ID des Posts
 * @return string EMail-Adresse.
 */
function get_post_email($post_id) {
    return get_post_meta($post_id, 'bd_email', FALSE)[0];
}

/**
 * Liefert die URL der Webseite eines Unternehmens-Eintrag.
 * @param int $post_id ID des Posts
 * @return string URL der Webseite.
 */
function get_post_website($post_id) {
    return get_post_meta($post_id, 'bd_website', FALSE)[0];
}

/**
 * Liefert den Längengrad eines Unternehmens-Eintrag.
 * @param int $post_id ID des Posts
 * @return string Längengrad
 */
function get_post_longitude($post_id) {
    return get_post_meta($post_id, 'bd_longitude', FALSE)[0];
}

/**
 * Liefert den Breitengrad eines Unternehmens-Eintrag.
 * @param int $post_id ID des Posts
 * @return string Breitengrad
 */
function get_post_latitude($post_id) {
    return get_post_meta($post_id, 'bd_latitude', FALSE)[0];
}

/**
 * Liefert die Beschreibung eines Unternehmens-Eintrag.
 * @param int $post_id ID des Posts
 * @return string Beschreibung eines Unternehmens
 */
function get_post_content($post_id) {
    $post = get_post($post_id);
    return apply_filters('the_content', $post->post_content);
}

/**
 * Prüft, ob ein Post noch angezeigt werden darf, oder ob der Unternehmer seit
 * über 30 Tagen nicht mehr angemeldet war.
 * @param int $post_id ID des Posts
 * @return bool true, wenn der Eintrag noch aktuell ist, ansonsten false.
 */
function post_is_up_to_date($post_id) {
    $last_login = get_post_meta($post_id, 'bd_last_login', FALSE)[0];
    $time = time() - intval($last_login);
    return $time < 60 * 60 * 24 * 30;
}

/**
 * Sucht den Post zu einer Google-ID.
 * @param string $id Google-iD
 * @return Post zur Google-ID oder null.
 */
function get_post_by_google_id($id) {
    $posts = get_posts(array(
        'numberposts' => -1,
        'post_type' => 'company',
        'meta_key' => 'bd_google_id',
        'meta_value' => $id
    ));
    if (count($posts) > 0) {
        return $posts[0];
    }
    return null;
}

/**
 * Setzt die Zeit des letzten Logins auf die aktuelle Zeit.
 * @param type $post_id ID des Posts.
 */
function update_last_login_time($post_id) {
    update_post_meta($post_id, 'bd_last_login', time());
}

/**
 * Gibt eine Tabelle mit den Parametern des Unternehmens aus.
 * @param Post $post Post, für den der Eintrag ausgegeben werden soll.
 * @param bool $print_heading soll eine Überschrift mit Link zur Detailseite ausgegeben werden?
 * @param string $distance wenn gesetzt, wird die Distanz im Eintrag mit ausgegeben.
 */
function print_company_entry($post, $print_heading, $distance = null) {
    // Überschrift
    echo '<table><tr><td>';
    if ($print_heading) {
        echo '<h4><a href="' . get_the_permalink($post->ID) . '">' . get_the_title($post->ID) . '</a></h4><small>';
        if ($distance != null) {
            echo "<b>" . number_format(floatval($distance), 2) . "km</b><br>";
        }
    }

    // Gewerbe
    foreach (wp_get_post_terms($post->ID, 'type') as $tag) {
        echo '<span class="label label-default">' . $tag->name . '</span>&nbsp;';
    }
    echo '<br><br>';

    // Adresse
    echo '<table class="company-entry"><tr><td><i class="icon ion-location"></i></td><td><small>';
    echo get_post_meta($post->ID, 'bd_street', FALSE)[0] . " ";
    echo get_post_meta($post->ID, 'bd_housenr', FALSE)[0] . "<br>";
    echo get_post_meta($post->ID, 'bd_plz', FALSE)[0] . " ";
    echo get_post_meta($post->ID, 'bd_city', FALSE)[0] . "</small></td></tr>";

    // Telefonnummer
    echo '<tr><td><i class="icon ion-ios-telephone"></i></td><td><small>';
    echo get_post_meta($post->ID, 'bd_telephone', FALSE)[0] . "</small></td></tr>";

    // EMail
    echo '<tr><td><i class="icon ion-email"></i></td><td><small>';
    echo '<a href="mailto:' . get_post_meta($post->ID, 'bd_email', FALSE)[0] . '">' . get_post_meta($post->ID, 'bd_email', FALSE)[0] . '</a></small></td></tr>';

    // Website
    echo '<tr><td><i class="icon ion-earth"></i></td><td><small>';
    echo '<a href="' . get_post_meta($post->ID, 'bd_website', FALSE)[0] . '">' . get_post_meta($post->ID, 'bd_website', FALSE)[0] . '</a></small></td></tr></table>';
}

?>