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

function add_post($title, $type, $plz, $city, $street, $housenr, $telephone, $email, $website, $longitude, $latitude) {
    echo "GEWERBE: " . $type;
    // neuen Post erzeugen
    $post_id = wp_insert_post(array(
        'post_type' => 'company',
        'post_title' => $title,
        'post_status' => 'publish'
    ));
    // Coustom-Fields setzen
    add_post_meta($post_id, 'bd_plz', $plz);
    add_post_meta($post_id, 'bd_city', $city);
    add_post_meta($post_id, 'bd_street', $street);
    add_post_meta($post_id, 'bd_housenr', $housenr);
    add_post_meta($post_id, 'bd_telephone', $telephone);
    add_post_meta($post_id, 'bd_email', $email);
    add_post_meta($post_id, 'bd_website', $website);
    add_post_meta($post_id, 'bd_longitude', $longitude);
    add_post_meta($post_id, 'bd_latitude', $latitude);
    // Tags (Gewerbe) hinzufügen
    wp_set_object_terms($post_id, explode(",", $type), 'type');
}

if (isset($_POST['bd_company_title'])) {
    $title = $_POST['bd_company_title'];
    $type = $_POST['tag_type'];
    $plz = $_POST['bd_plz'];
    $city = $_POST['bd_city'];
    $street = $_POST['bd_street'];
    $housenr = $_POST['bd_housenr'];
    $telephone = $_POST['bd_telephone'];
    $email = $_POST['bd_email'];
    $website = $_POST['bd_website'];
    $longitude = $_POST['bd_longitude'];
    $latitude = $_POST['bd_latitude'];
    add_post($title, $type, $plz, $city, $street, $housenr, $telephone, $email, $website, $longitude, $latitude);
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

            <form action="<?php the_permalink(); ?>" method="post" class="form-horizontal">                

                <!-- Gewerbe -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Gewerbe</label>
                    <table>
                        <tr>
                            <td>
                                <select id="selectType">
                                    <?php
                                    $tags = get_terms('type', 'hide_empty=0');
                                    foreach ($tags as $tag) {
                                        echo '<option value="' . $tag->slug . '">' . $tag->name . '</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <a class="btn btn-default" role="button" onClick="addType()"><i class="icon ion-plus"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" class="form-control" id="inputTypes" name="tag_type" readonly>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="form-group">
                    <label for="inputCompany" class="col-sm-2 control-label">Firma</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputCompany" placeholder="Firma" name="bd_company_title">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPLZ" class="col-sm-2 control-label">PLZ</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputPLZ" placeholder="PLZ" name="bd_plz">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputCity" class="col-sm-2 control-label">Ort</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputCity" placeholder="Ort" name="bd_city">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputStreet" class="col-sm-2 control-label">Straße</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputStreet" placeholder="Straße" name="bd_street">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputHouseNr" class="col-sm-2 control-label">Hausnummer</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputHouseNr" placeholder="Hausnummer" name="bd_housenr">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputTelephone" class="col-sm-2 control-label">Telefonnummer</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputTelephone" placeholder="Telefonnummer" name="bd_telephone">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEMail" class="col-sm-2 control-label">EMail</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputEMail" placeholder="EMail-Adresse" name="bd_email">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputWebsite" class="col-sm-2 control-label">Web-Seite</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputWebsite" placeholder="Web-Seite" name="bd_website">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputLongitude" class="col-sm-2 control-label">Längengrad</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputLongitude" placeholder="Längengrad" name="bd_longitude">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputLatitude" class="col-sm-2 control-label">Breitengrad</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputLatitude" placeholder="Breitengrad" name="bd_latitude">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-default">Firma eintragen</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script type="text/javascript">
// Hinzufügen von Gewerbe-Tags

    function addType() {
        var type = $("#selectType").val();
        var types_input = $("#inputTypes");
        if (types_input.val() == "")
            types_input.val(type);
        else
            types_input.val(types_input.val() + "," + type);
    }
</script>

<?php get_footer(); ?>
