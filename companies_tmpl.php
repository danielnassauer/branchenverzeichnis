<?php
/**
 * Template Name: Companies
 * */

/**
 * 
 * @return type
 */
function search_companies() {
    if (!isset($_POST['latitude']))
        return;
    $lat = $_POST['latitude'];
    $long = $_POST['longitude'];
    echo "Ergebnise für " . $lat . " " . $long;
}
?>

<?php get_header(); ?>
<div class="container-fluid">
    <div class="row">
        <?php get_sidebar(); ?>        
        <div class="col-md-10">            
            <div id="map" class="map-top"></div>

            <!-- SUCHFORMULAR -->
            <div class="panel panel-default search-box">
                <div class="panel-heading">
                    <h3 class="panel-title">Suche</h3>
                </div>                
                <div class="panel-body">     
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
                            <div class="col-sm-offset-2 col-sm-10">
                                <button class="btn btn-primary" type="submit">Suchen</button>
                            </div>
                        </div>
                        <input type="hidden" id="latitude" name="latitude">
                        <input type="hidden" id="longitude" name="longitude">
                    </form>
                </div>
            </div>

            <!-- ERGEBNISSE -->
            <div class="panel panel-default search-box">
                <div class="panel-heading">
                    <h3 class="panel-title">Ergebnisse</h3>
                </div>                
                <div class="panel-body">
                    <?php search_companies() ?>
                    <table class="table table-hover table-striped">
                        <?php
                        $type = 'company';
                        $args = array(
                            'post_type' => $type,
                            'post_status' => 'publish',
                            'posts_per_page' => -1,
                            'caller_get_posts' => 1);

                        $my_query = null;
                        $my_query = new WP_Query($args);
                        if ($my_query->have_posts()) {
                            while ($my_query->have_posts()) : $my_query->the_post();
                                ?>
                                <tr>
                                    <td>
                                        <h3>
                                            <a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a>
                                        </h3>
                                        <p>
                                            <?php echo get_post_meta($post->ID, 'bd_street', FALSE)[0] ?>
                                            <?php echo get_post_meta($post->ID, 'bd_housenr', FALSE)[0] ?>
                                            <br>
                                            <?php echo get_post_meta($post->ID, 'bd_plz', FALSE)[0] ?>
                                            <?php echo get_post_meta($post->ID, 'bd_city', FALSE)[0] ?>
                                        </p>
                                    </td>
                                </tr>
                                <?php
                            endwhile;
                        }
                        wp_reset_query();
                        ?>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>




<script type="text/javascript">
    function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 8,
            center: {lat: -34.397, lng: 150.644}
        });
        var geocoder = new google.maps.Geocoder();

        document.getElementById('submit').addEventListener('click', function () {
            geocodeAddress(geocoder, map);
        });
    }

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
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCLgez1M-ZaVnXYnl1gJtx_EQFWiK-gZT0&signed_in=true&callback=initMap"
async defer></script>

<?php get_footer(); ?>
