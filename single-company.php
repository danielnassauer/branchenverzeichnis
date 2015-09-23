<?php get_header(); ?>
<div class="container-fluid">
    <div class="row">
        <?php get_sidebar(); ?>

        <script src="http://maps.googleapis.com/maps/api/js"></script>

        <script>
            var long = <?php echo get_post_meta($post->ID, 'bd_longitude', FALSE)[0]; ?>;
            var lat = <?php echo get_post_meta($post->ID, 'bd_latitude', FALSE)[0]; ?>;
            var myCenter = new google.maps.LatLng(long, lat);

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

        <div class="col-md-10">
            <div id="googleMap" class="map-top"></div>
            <h2><?php the_title() ?></h2>
            <p>
                <?php echo get_post_meta($post->ID, 'bd_street', FALSE)[0] ?>
                <?php echo get_post_meta($post->ID, 'bd_housenr', FALSE)[0] ?>
                <br>
                <?php echo get_post_meta($post->ID, 'bd_plz', FALSE)[0] ?>
                <?php echo get_post_meta($post->ID, 'bd_city', FALSE)[0] ?>
            </p>
        </div>
    </div>
</div>

<?php get_footer(); ?>
