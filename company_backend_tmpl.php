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

/**
 * Prüft, ob der angemeldete Benutzer bereits einen Eintrag verfasst hat.
 * @return bool true, wenn der angemeldete Benutzer bereits einen Eintrag verfasst hat, ansonsten false.
 */
function user_has_post() {
    return get_post_by_google_id(google_get_user_id()) != null;
}

/**
 * Erzeugt das Datum des letzten Logins als Text.
 * Die Funktion sollte aufgerufen werden, bevor der letzete Login geupdated wird.
 * @return string Datum des letzten Logins im Format dd.mm.YY
 */
function get_last_login_date() {
    date_default_timezone_set("Europe/Berlin");
    $company_post = get_post_by_google_id(google_get_user_id());
    if ($company_post != null) {
        $last_login = get_post_meta($company_post->ID, 'bd_last_login', FALSE)[0];
        return date('d.m.Y', $last_login);
    }
}

// Wird ein neues Unternehmen angelegt?
if (isset($_POST['create_new_entry'])) {
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

// Wird ein bestehender Eintrag geändert?
elseif (isset($_POST['update_entry'])) {
    $content = $_POST['bd_content'];
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
    update_post($content, $plz, $city, $street, $housenr, $telephone, $email, $website, $longitude, $latitude, $user_id);
}

// Wird ein Eintrag gelöscht?
elseif (isset($_POST['delete_entry'])) {
    $user_id = $_POST['bd_google_id'];
    delete_post(get_post_by_google_id(google_get_user_id())->ID);
}

$company_post = get_post_by_google_id(google_get_user_id());
?>

<?php get_header(); ?>

<div class="container-fluid sidenav-container">  
    <div class="row">
        <?php get_sidebar(); ?>        
        <div class="col-md-10 background content">
            <h3>Willkommen, <?php echo google_get_user_name() ?>!</h3>

            <?php if (user_has_post()) { ?>
                <p>Sie waren zuletzt am <b><?php echo get_last_login_date() ?></b>
                    angemeldet.
                    Bitte bedenken Sie, dass nach 30 Tagen Abwesenheit ihr Eintrag nicht
                    mehr angezeigt wird!</p>
                <?php update_last_login_time($company_post->ID); ?>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Eintrag anschauen</h3>
                    </div>
                    <div class="panel-body">
                        Schauen Sie sich ihren Eintrag an, so wie ihn andere Benutzer sehen:<br>
                        <a href="<?php echo get_permalink($company_post) ?>">anschauen</a>
                    </div>
                </div>

                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <h3 class="panel-title">Eintrag löschen</h3>
                    </div>
                    <div class="panel-body">
                        Wenn Sie ihren Eintrag löschen möchten, klicken Sie auf diesen Button:
                        <br><br>
                        <form action="<?php the_permalink(); ?>" method="post" class="form-horizontal">                                                         
                            <input type='hidden' name='bd_google_id' value='<?php echo google_get_user_id() ?>'>  
                            <button type="submit" class="btn btn-default" name="delete_entry">Eintrag löschen</button>
                        </form>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Eintrag bearbeiten</h3>
                    </div>
                    <div class="panel-body">
                        <p>
                            Falls Sie Änderungen an ihrem Eintrag vornehmen möchten,
                            können Sie dies in folgendem Formular tun:
                        </p>
                        <form action="<?php the_permalink(); ?>" method="post" class="form-horizontal">                                    
                            <div class="form-group">
                                <label for="inputContent" class="col-sm-2 control-label">Beschreibung</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" id="inputContent" rows="5" name="bd_content">
                                        <?php echo strip_tags(get_post_content($company_post->ID)) ?>
                                    </textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPLZ" class="col-sm-2 control-label">PLZ</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="inputPLZ" placeholder="PLZ" name="bd_plz" value="<?php echo get_post_plz($company_post->ID) ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputCity" class="col-sm-2 control-label">Ort</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="inputCity" placeholder="Ort" name="bd_city" value="<?php echo get_post_city($company_post->ID) ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputStreet" class="col-sm-2 control-label">Straße</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="inputStreet" placeholder="Straße" name="bd_street" value="<?php echo get_post_street($company_post->ID) ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputHouseNr" class="col-sm-2 control-label">Hausnummer</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="inputHouseNr" placeholder="Hausnummer" name="bd_housenr" value="<?php echo get_post_housenr($company_post->ID) ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputTelephone" class="col-sm-2 control-label">Telefonnummer</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="inputTelephone" placeholder="Telefonnummer" name="bd_telephone" value="<?php echo get_post_telephone($company_post->ID) ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEMail" class="col-sm-2 control-label">EMail</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="inputEMail" placeholder="EMail-Adresse" name="bd_email" value="<?php echo get_post_email($company_post->ID) ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputWebsite" class="col-sm-2 control-label">Web-Seite</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="inputWebsite" placeholder="Web-Seite" name="bd_website" value="<?php echo get_post_website($company_post->ID) ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputLongitude" class="col-sm-2 control-label">Längengrad</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="inputLongitude" placeholder="Längengrad" name="bd_longitude" value="<?php echo get_post_longitude($company_post->ID) ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputLatitude" class="col-sm-2 control-label">Breitengrad</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="inputLatitude" placeholder="Breitengrad" name="bd_latitude" value="<?php echo get_post_latitude($company_post->ID) ?>">
                                </div>
                            </div>
                            <input type='hidden' name='bd_google_id' value='<?php echo google_get_user_id() ?>'>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-default" name="update_entry">Eintrag ändern</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>


            <?php } else { ?>
                <p>                    
                    Sie haben noch keinen Eintrag angelegt. Legen Sie bitte einen Eintrag für ihr Unternehmen an:
                </p>
                <br>
                <form action="<?php the_permalink(); ?>" method="post" class="form-horizontal">                

                    <!-- Gewerbe -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Gewerbe</label>
                        <div class="col-sm-10">
                            <table>
                                <tr>
                                    <td colspan="3">
                                        <small>
                                            Fügen Sie ihrem Unternehmen Gewerbe als Tags hinzu.<br>
                                            Sie können soviele Gewerbe angeben, wie sie möchten.<br>
                                            Wählen Sie ein bereits existierende Gewerbe aus der Liste oder fügen Sie eigene hinzu.
                                        </small>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        bestehende Gewerbe
                                    </td>
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
                                        neues Gewerbe
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" id="inputNewType">
                                    </td>
                                    <td>
                                        <a class="btn btn-default" role="button" onClick="addNewType()"><i class="icon ion-plus"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
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
                            <input type="number" class="form-control" id="inputPLZ" placeholder="PLZ" name="bd_plz">
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
                        <div class="col-sm-offset-2 col-sm-10">
                            <a onclick="fillLatLong()">Koordinaten automatisch finden</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputLongitude" class="col-sm-2 control-label">Längengrad</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="inputLongitude" placeholder="Längengrad" name="bd_longitude">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputLatitude" class="col-sm-2 control-label">Breitengrad</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="inputLatitude" placeholder="Breitengrad" name="bd_latitude">
                        </div>
                    </div>
                    <input type='hidden' name='bd_google_id' value='<?php echo google_get_user_id() ?>'>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-default" name="create_new_entry">Firma eintragen</button>
                        </div>
                    </div>
                </form>
            <?php }; ?>

        </div>
    </div>
</div>


<script type="text/javascript">

    /**
     * Fügt dem Tags-Feld das ausgewählte Tag aus der Liste hinzu.
     */
    function addType() {
        var type = $("#selectType").val();
        var types_input = $("#inputTypes");
        if (types_input.val() == "")
            types_input.val(type);
        else
            types_input.val(types_input.val() + "," + type);
    }

    /**
     * Fügt dem Tags-Feld ein neues Tag aus dem Eingabe-Feld hinzu.
     */
    function addNewType() {
        var type = $("#inputNewType").val();
        $("#inputNewType").val("");
        var types_input = $("#inputTypes");
        if (types_input.val() == "")
            types_input.val(type);
        else
            types_input.val(types_input.val() + "," + type);
    }

    /**
     * Sucht die passenden Koordinaten zur gewählten Adresse und fügt sie
     * in die entsprechenden Formular-Felder.
     */
    function fillLatLong() {
        var geocoder = new google.maps.Geocoder();
        var address = document.getElementById('inputCity').value + ", "
                + document.getElementById('inputStreet').value + " "
                + document.getElementById('inputHouseNr').value;
        geocoder.geocode({'address': address}, function (results, status) {
            if (status === google.maps.GeocoderStatus.OK) {
                document.getElementById('inputLatitude').value = results[0].geometry.location.H;
                document.getElementById('inputLongitude').value = results[0].geometry.location.L;
            } else {
                alert('Geocode was not successful for the following reason: ' + status);
            }
        });
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCLgez1M-ZaVnXYnl1gJtx_EQFWiK-gZT0&signed_in=true&callback=initMap"
async defer></script>

<?php get_footer(); ?>
