<?php

define('GOOGLE_CLIENT_ID', '1042880545551-5qeha6pmrq4r72a48p5f7r0bsqqdm1o5.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', 'rz8u-mFkRkeBTOzOC4ZqqC2N');
define('GOOGLE_REDIRECT_URI', 'http://localhost/daniel/wordpress/index.php/unternehmer-backend/');

/**
 * Führt eine Weiterleitung zum Google Authentifizierungsdienst durch.
 * Nachdem der Nutzer dort seine Daten eingegeben hat, wird er weitergeleitet
 * an die Redirect-URI, also zu der Seite, auf der Unterehmer ihren Eintrag
 * verwalten können.
 */
function google_sign_in() {
    $url = "https://accounts.google.com/o/oauth2/auth";

    $params = array(
        "response_type" => "code",
        "client_id" => GOOGLE_CLIENT_ID,
        "redirect_uri" => GOOGLE_REDIRECT_URI,
        "scope" => "https://www.googleapis.com/auth/plus.me"
    );

    $request_to = $url . '?' . http_build_query($params);
    header("Location: " . $request_to);
}

/**
 * Wird nach durchgeführter Weiterleitung an die Redirect-URI aufgerufen.
 */
function google_authenticate() {
    require_once realpath(dirname(__FILE__) . '/src/Google/autoload.php');

    $user = null;

    $client = new Google_Client();
    $client->setClientId(GOOGLE_CLIENT_ID);
    $client->setClientSecret(GOOGLE_CLIENT_SECRET);
    $client->setRedirectUri(GOOGLE_REDIRECT_URI);
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
        $_SESSION["GOOGLE_USER"] = $user;
    } else {
        $authUrl = $client->createAuthUrl();
    }
}

/**
 * Prüft, ob der Benutzer sich bereits mit seinem Google-Account angemeldet hat.
 * @return true, wenn der Benutzer angemeldet ist, ansonsten false
 */
function google_is_signed_in() {
    return isset($_SESSION["GOOGLE_USER"]);
}

/**
 * Gibt den Namen des Google-Benutzers zurück.
 * @return Name des Google-Benutzer oder null, falls niemand angemeldet ist.
 */
function google_get_user_name() {
    if (isset($_SESSION["GOOGLE_USER"])) {
        return $_SESSION["GOOGLE_USER"]['displayName'];
    }
    return null;
}

/**
 * Gibt Google-ID des Google-Benutzers zurück.
 * @return ID des Google-Benutzer oder null, falls niemand angemeldet ist.
 */
function google_get_user_id() {
    if (isset($_SESSION["GOOGLE_USER"])) {
        return $_SESSION["GOOGLE_USER"]['id'];
    }
    return null;
}

?>
