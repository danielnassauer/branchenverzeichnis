<?php
/**
 * Template Name: Company Backend
 * */
require_once 'google_auth.php';
require_once 'posts.php';

// Ist der Benutzer angemeldet? Ansonsten Anmelden.
if (!google_is_signed_in()) {
    google_sign_in();
}

google_authenticate();

function user_has_post() {
    return get_post_by_google_id(google_get_user_id()) != null;
}

function get_last_login_date() {
    date_default_timezone_set("Europe/Berlin");
    $company_post = get_post_by_google_id(google_get_user_id());
    if ($company_post != null) {
        $last_login = get_post_meta($company_post->ID, 'bd_last_login', FALSE)[0];
        return date('d.m.Y', $last_login);
    }
}

// Wird ein neues Unternehmen angelegt?
if (isset($_POST['bd_company_title'])) {
    $title = $_POST['bd_company_title'];
    $content = $_POST['bd_content'];
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
    $user_id = $_POST['bd_google_id'];
    add_post($title, $content, $type, $plz, $city, $street, $housenr, $telephone, $email, $website, $longitude, $latitude, $user_id);
}

$company_post = get_post_by_google_id(google_get_user_id());
?>

<?php get_header(); ?>

<div class="container-fluid">
    <div class="row">
        <?php get_sidebar(); ?>        
        <div class="col-md-10">
            <h2>UNTERNEHMERBACKEND</h2>  

            <?php if (user_has_post()) { ?>
                Hallo, <?php echo google_get_user_name() ?>!<br>
                Sie waren zuletzt am <b><?php echo get_last_login_date() ?></b>
                angemeldet.
                Bitte bedenken Sie, dass nach 30 Tagen Abwesenheit ihr Eintrag nicht
                mehr angezeigt wird!
                <?php
                update_last_login_time($company_post->ID);
            } else {
                ?>

                <form action="<?php the_permalink(); ?>" method="post" class="form-horizontal">                

                    <!-- Gewerbe -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Gewerbe</label>
                        <div class="col-sm-10">
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
                    </div>

                    <div class="form-group">
                        <label for="inputCompany" class="col-sm-2 control-label">Firma</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputCompany" placeholder="Firma" name="bd_company_title">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputContent" class="col-sm-2 control-label">Beschreibung</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="inputContent" rows="5" name="bd_content"></textarea>
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
                    <input type='hidden' name='bd_google_id' value='<?php echo google_get_user_id() ?>'>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-default">Firma eintragen</button>
                        </div>
                    </div>
                </form>
            <?php }; ?>

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
