<div class="media">
    
    <?php if( is_single() ) : ?>
        <h1 class="post-title">
            <?php the_title(); ?>
        </h1>
    <?php else : ?>
        <h3 class="post-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>
    <?php endif ; ?>
    
    <p class="post-info">
        Posté le <?php the_date(); ?> dans <?php the_category(', '); ?> par <?php the_author(); ?>.
    </p>
    <div class="post-content">
        <?php the_content(); ?>
    </div>
    
</div>