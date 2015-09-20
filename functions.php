<?php

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
            'post-thumbnails',
            'custom-fields'),
        'register_meta_box_cb' => 'add_metaboxes'
            )
    );
}

function add_metaboxes() {
    add_meta_box('bd_plz', 'PLZ', 'bd_plz_html', 'company', 'normal', 'default');
    add_meta_box('bd_city', 'Ort', 'bd_city_html', 'company', 'normal', 'default');
    add_meta_box('bd_street', 'Strasse', 'bd_street_html', 'company', 'normal', 'default');
    add_meta_box('bd_housenr', 'Hausnummer', 'bd_housenr_html', 'company', 'normal', 'default');
}

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

    foreach ($events_meta as $key => $value) {
        if ($post->post_type == 'revision')
            return;
        $value = implode(',', (array) $value);
        if (get_post_meta($post->ID, $key, FALSE)) {
            update_post_meta($post->ID, $key, $value);
        } else {
            add_post_meta($post->ID, $key, $value);
        }
        if (!$value)
            delete_post_meta($post->ID, $key);
    }
}

function bd_input_html($id) {
    global $post;
    echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';
    $data = get_post_meta($post->ID, $id, true);
    echo '<input type="text" name="' . $id . '" value="' . $data . '" class="widefat" />';
}

function bd_plz_html() {
    bd_input_html('bd_plz');
}

function bd_city_html() {
    bd_input_html('bd_city');
}

function bd_street_html() {
    bd_input_html('bd_street');
}

function bd_housenr_html() {
    bd_input_html('bd_housenr');
}

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

if (isset($_GET['activated']) && is_admin()) {
    add_page('Unternehmer Login', 'company_login_tmpl.php');
    add_page('Unternehmer Backend', 'company_backend_tmpl.php');
    add_page('Firmen', 'companies_tmpl.php');
}

add_action('init', 'add_post_types');
add_action('save_post', 'bd_save_meta', 1, 2);

//TODO nur zum testen
flush_rewrite_rules();
?>