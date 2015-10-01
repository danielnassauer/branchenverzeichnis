<?php
/**
 * Template Name: Companies
 * */
require_once 'geo.php';
require_once 'posts.php';

/**
 * Berechnet die Distanz in km für den aktuellen Eintrag.
 * @return float Distanz in km
 */
function get_distance() {
    global $post;
    $lat_origin = $_POST['latitude'];
    $long_origin = $_POST['longitude'];
    $lat_company = get_post_meta($post->ID, 'bd_latitude', FALSE)[0];
    $long_company = get_post_meta($post->ID, 'bd_longitude', FALSE)[0];
    return get_geo_distance($lat_origin, $long_origin, $lat_company, $long_company);
}
?>

<?php get_header(); ?>
<script type="text/javascript">
    var coordinates = [];
</script>

<div class="container-fluid sidenav-container">    
    <div class="row">
        <?php get_sidebar(); ?>      

        <div class="col-md-10 background map-container">
            <div id="map" class="map-top"></div>
        </div>

        <div class="col-md-10 col-md-offset-2 background content">                        

            <!-- SUCHFORMULAR -->
            <div class="well">                
                <div class="form-horizontal">
                    <div class="form-group">
                        <label for="address" class="col-sm-2 control-label">Ort</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="address" placeholder="z.B. Berlin oder Berlin, Potsdamer Straße 1">
                            <button class="btn btn-default" id="submit" value="Geocode">ausw&auml;hlen</button>
                        </div>                            
                    </div>  
                </div>
                <form action="<?php the_permalink(); ?>" method="post" class="form-horizontal">
                    <div class="form-group">
                        <label for="radius" class="col-sm-2 control-label">Gewerbe</label>
                        <div class="col-sm-10"> 
                            <select id="selectType" name="tag_type">
                                <?php
                                $tags = get_terms('type', 'hide_empty=0');
                                foreach ($tags as $tag) {
                                    echo '<option value="' . $tag->slug . '">' . $tag->name . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="radius" class="col-sm-2 control-label">Umkreis</label>
                        <div class="col-sm-10">                                
                            <input type="number" class="form-control" id="radius" name="radius" placeholder="geben Sie den Umkreis in km ein">
                        </div>                            
                    </div>                    
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button class="btn btn-primary" type="submit">Suchen</button>
                        </div>
                    </div>
                    <input type="hidden" id="latitude" name="latitude">
                    <input type="hidden" id="longitude" name="longitude">
                </form>
            </div>

            <!-- ERGEBNISSE -->
            <h3>Ergebnisse</h3>



            <?php
            $type = 'company';
            $args = array(
                'post_type' => $type,
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'caller_get_posts' => 1);
            if (isset($_POST["tag_type"])) {
                $args["type"] = $_POST["tag_type"];
            }

            $my_query = new WP_Query($args);
            if ($my_query->have_posts()) {
                while ($my_query->have_posts()) : $my_query->the_post();
                    ?>
                    <?php
                    if (post_is_up_to_date($post->ID)) {
                        if (isset($_POST['latitude'])) {
                            $dist = get_distance();
                            $radius = floatval($_POST["radius"]);
                            if ($dist <= $radius) {
                                print_company_entry($post, true, $dist);
                                $lat = get_post_latitude($post->ID);
                                $lng = get_post_longitude($post->ID);
                                echo '<script type="text/javascript">coordinates.push({lat: ' . $lat . ', lng: ' . $lng . ', company: "' . get_the_title() . '"})</script>';
                                echo "<hr>";
                            }
                        } 
//                        else {
//                            print_company_entry($post, true);
//                            echo "<hr>";
//                        }
                    }
                    ?>
                    <?php
                endwhile;
            }
            wp_reset_query();
            ?>                  

        </div>
    </div>
</div>




<script type="text/javascript">
    /**
     * Callback-Funktion. Wird von Google aufgerufen und Initialisiert die Karte.
     */
    function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 12,
            center: {lat: 50.0, lng: 8.0}
        });

        var geocoder = new google.maps.Geocoder();

        document.getElementById('submit').addEventListener('click', function () {
            geocodeAddress(geocoder, map);
        });

        addMarkers(map);
    }

    /**
     * Löst eine in der Suche eingegebene Adresse zu Koordinaten auf.
     */
    function geocodeAddress(geocoder, resultsMap) {
        var address = document.getElementById('address').value;
        geocoder.geocode({'address': address}, function (results, status) {
            if (status === google.maps.GeocoderStatus.OK) {
                resultsMap.setCenter(results[0].geometry.location);
                var marker = new google.maps.Marker({
                    map: resultsMap,
                    position: results[0].geometry.location
                });
                document.getElementById('latitude').value = results[0].geometry.location.H;
                document.getElementById('longitude').value = results[0].geometry.location.L;
            } else {
                alert('Geocode was not successful for the following reason: ' + status);
            }
        });
    }

    /**
     * Fügt Marker für alle Ergebnisse in der Karte ein. Klickt man auf einen Marker
     * wird eine Infobox angezeigt.
     */
    function addMarkers(map) {
        for (var i in coordinates) {
            var coordinate = coordinates[i];

            var marker = new google.maps.Marker({
                position: {lat: coordinate.lat, lng: coordinate.lng},
            });

            marker.setMap(map);

            map.setCenter({lat: coordinate.lat, lng: coordinate.lng});

            var infowindow = new google.maps.InfoWindow({
                content: coordinate.company
            });

            google.maps.event.addListener(marker, 'click', function () {
                infowindow.open(map, marker);
            });
        }
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCLgez1M-ZaVnXYnl1gJtx_EQFWiK-gZT0&signed_in=true&callback=initMap"
async defer></script>

<?php get_footer(); ?>
