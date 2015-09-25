<?php

/**
 * Template Name: Company Login
 * */
function signin() {
    $url = "https://accounts.google.com/o/oauth2/auth";
    $client_id = '1042880545551-5qeha6pmrq4r72a48p5f7r0bsqqdm1o5.apps.googleusercontent.com';
    $client_secret = 'rz8u-mFkRkeBTOzOC4ZqqC2N';

    $params = array(
        "response_type" => "code",
        "client_id" => $client_id,
        "redirect_uri" => "http://localhost/daniel/wordpress/index.php/unternehmer-backend/",
        "scope" => "https://www.googleapis.com/auth/plus.me"
    );

    $request_to = $url . '?' . http_build_query($params);
    header("Location: " . $request_to);
}

signin();
?>

<?php get_header(); ?>
<div class="container-fluid">
    <div class="row">
        <?php get_sidebar(); ?>        
        <div class="col-md-10">
            <h2>UNTERNEHMERLOGIN</h2>
        </div>
    </div>
</div>

<?php get_footer(); ?>
