<?php
/**
 * Template Name: Companies
 * */
?>

<?php get_header(); ?>
<div class="container-fluid">
    <div class="row">
        <?php get_sidebar(); ?>        
        <div class="col-md-10">
            <h2>FIRMEN</h2>
            <?php
            $type = 'companies';
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
                    <p><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></p>
                    <?php
                endwhile;
            }
            wp_reset_query();
            ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
