<?php get_header(); ?>
<div class="container-fluid">
    <div class="row">
        <?php get_sidebar(); ?>        
        <div class="col-md-10">
            <h2>FIRMA</h2>
            <p>
                PLZ: <?php echo get_post_meta($post->ID, 'bd_plz', FALSE)[0]; ?>
                <br>
                Ort: <?php echo get_post_meta($post->ID, 'bd_city', FALSE)[0]; ?>
                <br>
                Straße: <?php echo get_post_meta($post->ID, 'bd_street', FALSE)[0]; ?>
                <br>
                Hausnummer: <?php echo get_post_meta($post->ID, 'bd_housenr', FALSE)[0]; ?>
            </p>
        </div>
    </div>
</div>

<?php get_footer(); ?>
