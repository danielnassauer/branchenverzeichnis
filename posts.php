<?php

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

function post_is_up_to_date($post_id) {
    $last_login = get_post_meta($post_id, 'bd_last_login', FALSE)[0];
    $time = time() - intval($last_login);
    return $time < 60 * 60 * 24 * 30;
}

/**
 * Sucht den Post zu einer Google-ID.
 * @param type $id Google-iD
 * @return type Post zur Google-ID oder null.
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

function update_last_login_time($post_id) {
    update_post_meta($post_id, 'bd_last_login', time());
}

function print_company_entry($post, $print_heading, $distance = null) {
    // Überschrift
    echo '<table><tr><td>';
    if ($print_heading) {
        echo '<h4><a href="' . get_the_permalink($post->ID) . '">' . get_the_title($post->ID) . '</a></h4><small>';
        if ($distance != null) {
            echo "<b>" . $distance . "km</b><br>";
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