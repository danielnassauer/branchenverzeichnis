<?php get_header(); ?>

<div class="container-fluid sidenav-container">  
    <div class="row">
        <?php get_sidebar(); ?>        
        <div class="col-md-10 background"> 
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <h2><?php the_title(); ?></h2>        
                    <?php the_content(); ?>        
                <?php
                endwhile;
            endif;
            ?>
        </div>
    </div>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>