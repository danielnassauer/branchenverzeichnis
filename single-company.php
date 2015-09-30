<?php
require_once 'posts.php';
?>

<?php get_header(); ?>
<div class="container-fluid">
    <div class="row">
        <?php get_sidebar(); ?>
        <div class="col-md-10">
            <div id="googleMap" class="map-top"></div>
            <h2><?php the_title() ?></h2>
            <div class='panel panel-default'>
                <div class="panel-body">
                    <?php print_company_entry($post, false) ?>
                    <hr>
                    <?php echo apply_filters('the_content', $post->post_content); ?>
                </div>                
            </div>
        </div>
    </div>
</div>

<script src="http://maps.googleapis.com/maps/api/js"></script>
<script>
    var long = <?php echo get_post_meta($post->ID, 'bd_longitude', FALSE)[0]; ?>;
    var lat = <?php echo get_post_meta($post->ID, 'bd_latitude', FALSE)[0]; ?>;
    var myCenter = new google.maps.LatLng(lat, long);

    function initialize()
    {
        var mapProp = {
            center: myCenter,
            zoom: 5,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        var map = new google.maps.Map(document.getElementById("googleMap"), mapProp);

        var marker = new google.maps.Marker({
            position: myCenter,
        });

        marker.setMap(map);
    }

    google.maps.event.addDomListener(window, 'load', initialize);
</script> 

<?php get_footer(); ?>
