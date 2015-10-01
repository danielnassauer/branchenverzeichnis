<?php

define('WP_DEBUG', true);

////////////////////////////////////////////////////////////////////////////////
// POST TYPES
////////////////////////////////////////////////////////////////////////////////

/**
 * Fügt den Custom Post Type "company" hinzu, der einem Unternehmens-Eintrag 
 * entspricht.
 */
function add_post_types() {
    register_post_type(
            'company', array(
        'labels' => array(
            'name' => 'Firmen',
            'singular_name' => 'Firma',
        ),
        'public' => true,
        'show_ui' => true,
        'supports' => array(
            'title',
            'editor',
            'post-thumbnails',
            'custom-fields'),
        'register_meta_box_cb' => 'add_metaboxes'
            )
    );
}

/**
 * Fügt dem Custom Post Type "company" alle Parameter als Custom Fields hinzu.
 */
function add_metaboxes() {
    add_meta_box('bd_plz', 'PLZ', 'bd_plz_html', 'company', 'normal', 'default');
    add_meta_box('bd_city', 'Ort', 'bd_city_html', 'company', 'normal', 'default');
    add_meta_box('bd_street', 'Strasse', 'bd_street_html', 'company', 'normal', 'default');
    add_meta_box('bd_housenr', 'Hausnummer', 'bd_housenr_html', 'company', 'normal', 'default');
    add_meta_box('bd_telephone', 'Telefonnummer.', 'bd_telephone_html', 'company', 'normal', 'default');
    add_meta_box('bd_email', 'EMail', 'bd_email_html', 'company', 'normal', 'default');
    add_meta_box('bd_website', 'Web-Seite', 'bd_website_html', 'company', 'normal', 'default');
    add_meta_box('bd_housenr', 'Hausnummer', 'bd_housenr_html', 'company', 'normal', 'default');
    add_meta_box('bd_longitude', 'Längengrad', 'bd_longitude_html', 'company', 'normal', 'default');
    add_meta_box('bd_latitude', 'Breitengrad', 'bd_latitude_html', 'company', 'normal', 'default');
    add_meta_box('bd_google_id', 'Google-ID', 'bd_google_id_html', 'company', 'normal', 'default');
    add_meta_box('bd_last_login', 'Letzter Login', 'bd_last_login_html', 'company', 'normal', 'default');
}

/**
 * Wird aufgerufen, wenn ein Post gespeichert wird (im Back-End). Speichert die 
 * Custom Fields zu jedem Post.
 * @param int $post_id ID des Posts
 * @param Post $post der Post
 * @return int Post-ID
 */
function bd_save_meta($post_id, $post) {
    if (!wp_verify_nonce($_POST['eventmeta_noncename'], plugin_basename(__FILE__))) {
        return $post->ID;
    }

    if (!current_user_can('edit_post', $post->ID))
        return $post->ID;

    $events_meta['bd_plz'] = $_POST['bd_plz'];
    $events_meta['bd_city'] = $_POST['bd_city'];
    $events_meta['bd_street'] = $_POST['bd_street'];
    $events_meta['bd_housenr'] = $_POST['bd_housenr'];
    $events_meta['bd_telephone'] = $_POST['bd_telephone'];
    $events_meta['bd_email'] = $_POST['bd_email'];
    $events_meta['bd_website'] = $_POST['bd_website'];
    $events_meta['bd_longitude'] = $_POST['bd_longitude'];
    $events_meta['bd_latitude'] = $_POST['bd_latitude'];
    $events_meta['bd_google_id'] = $_POST['bd_google_id'];
    $events_meta['bd_last_login'] = $_POST['bd_last_login'];

    foreach ($events_meta as $key => $value) {
        if ($post->post_type == 'revision')
            return;
        $value = implode(',', (array) $value);
        if (get_post_meta($post->ID, $key, FALSE)) {
            update_post_meta($post->ID, $key, $value);
        } else {
            add_post_meta($post->ID, $key, $value);
        }
        if (!$value) {
            delete_post_meta($post->ID, $key);
        }
    }
}

/**
 * Gibt ein Text-Feld zur Eingabe von Werten für Custom-Fields im Back-End aus.
 * @param type $id ID des Custom Fields
 */
function bd_input_html($id) {
    global $post;
    echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';
    $data = get_post_meta($post->ID, $id, true);
    echo '<input type="text" name="' . $id . '" value="' . $data . '" class="widefat" />';
}

/**
 * Gibt ein Textfeld zur Eingabe der PLZ im Back-End aus.
 */
function bd_plz_html() {
    bd_input_html('bd_plz');
}

/**
 * Gibt ein Textfeld zur Eingabe der Stadt im Back-End aus.
 */
function bd_city_html() {
    bd_input_html('bd_city');
}

/**
 * Gibt ein Textfeld zur Eingabe der Straße im Back-End aus.
 */
function bd_street_html() {
    bd_input_html('bd_street');
}

/**
 * Gibt ein Textfeld zur Eingabe der Haus-Nr. im Back-End aus.
 */
function bd_housenr_html() {
    bd_input_html('bd_housenr');
}

/**
 * Gibt ein Textfeld zur Eingabe des Längengrades im Back-End aus.
 */
function bd_longitude_html() {
    bd_input_html('bd_longitude');
}

/**
 * Gibt ein Textfeld zur Eingabe des Breitengrades im Back-End aus.
 */
function bd_latitude_html() {
    bd_input_html('bd_latitude');
}

/**
 * Gibt ein Textfeld zur Eingabe der Telefon-Nr. im Back-End aus.
 */
function bd_telephone_html() {
    bd_input_html('bd_telephone');
}

/**
 * Gibt ein Textfeld zur Eingabe der EMail-Adresse im Back-End aus.
 */
function bd_email_html() {
    bd_input_html('bd_email');
}

/**
 * Gibt ein Textfeld zur Eingabe der URL der Webseite im Back-End aus.
 */
function bd_website_html() {
    bd_input_html('bd_website');
}

/**
 * Gibt ein Textfeld zur Eingabe der Google-ID im Back-End aus.
 */
function bd_google_id_html() {
    bd_input_html('bd_google_id');
}

/**
 * Gibt ein Textfeld zur Eingabe der Zeit des letzten Logins im Back-End aus.
 */
function bd_last_login_html() {
    bd_input_html('bd_last_login');
}

add_action('admin_init', 'add_post_types');
add_action('save_post', 'bd_save_meta', 1, 2);

////////////////////////////////////////////////////////////////////////////////
// PAGES
////////////////////////////////////////////////////////////////////////////////

/**
 * Erzeugt eine neue Seite, falls sie noch nicht existiert.
 * @param string $new_page_title Titel der neuen Seite.
 * @param string $new_page_template Template-Datei der neuen Seite.
 */
function add_page($new_page_title, $new_page_template) {
    $new_page_content = '';
    $page_check = get_page_by_title($new_page_title);
    $new_page = array(
        'post_type' => 'page',
        'post_title' => $new_page_title,
        'post_content' => $new_page_content,
        'post_status' => 'publish',
        'post_author' => 1,
    );
    if (!isset($page_check->ID)) {
        $new_page_id = wp_insert_post($new_page);
        if (!empty($new_page_template)) {
            update_post_meta($new_page_id, '_wp_page_template', $new_page_template);
        }
    }
}

/**
 * Fügt die Seiten "Unternehmer Login" und "Suche" ein.
 */
function add_pages() {
    add_page('Unternehmer Login', 'company_backend_tmpl.php');
    add_page('Suche', 'companies_tmpl.php');
}

add_action('admin_init', 'add_pages');

////////////////////////////////////////////////////////////////////////////////
// TAXONOMIES, TERMS
////////////////////////////////////////////////////////////////////////////////

/**
 * Fügt die Gewerbe-Taxonomy hinzu.
 */
function create_taxonomies() {
    $types = array('Arzt', 'Friseur', 'Restaurant', 'Supermarkt');

    register_taxonomy('type', 'company', array(
        'label' => 'Gewerbe',
        'hierarchical' => false)
    );
    foreach ($types as $type) {
        wp_insert_term($type, 'type');
    }
}

add_action('init', 'create_taxonomies');

////////////////////////////////////////////////////////////////////////////////
flush_rewrite_rules();
?>