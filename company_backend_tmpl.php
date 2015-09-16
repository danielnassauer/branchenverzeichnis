<?php

/**
 * Template Name: Company Backend
 * */
session_start();
require_once realpath(dirname(__FILE__) . '/src/Google/autoload.php');

$user = null;

$client_id = '1042880545551-5qeha6pmrq4r72a48p5f7r0bsqqdm1o5.apps.googleusercontent.com';
$client_secret = 'rz8u-mFkRkeBTOzOC4ZqqC2N';
$redirect_uri = 'http://localhost/daniel/wordpress/index.php/unternehmer-backend/';

$client = new Google_Client();
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setRedirectUri($redirect_uri);
$client->addScope("https://www.googleapis.com/auth/plus.me");

$service = new Google_Service_Plus($client);

if (isset($_GET['code'])) {
    $client->authenticate($_GET['code']);
    $_SESSION['access_token'] = $client->getAccessToken();
    $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
    header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
}

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);
    $user = $service->people->get('me');
} else {
    $authUrl = $client->createAuthUrl();
}
?>

<?php get_header(); ?>
<div class="container-fluid">
    <div class="row">
        <?php get_sidebar(); ?>        
        <div class="col-md-10">
            <h2>UNTERNEHMERBACKEND</h2>
            Angemeldet als: <?php echo $user['displayName'] ?><br>
            ID: <?php echo $user['id'] ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
