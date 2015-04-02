<div class="row">
    <div class="span8">

        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <?php get_template_part( "post" ); ?>
            <?php endwhile; ?>
        <?php else : ?>
            <p class="nothing">
                Il n'y a pas de Post à afficher !
            </p>
        <?php endif; ?>
        
    </div>
    <div class="span4">
        
        <?php get_sidebar(); ?>
        
    </div>
</div>
