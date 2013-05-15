
<div class="sidebar-nav well">

    <ul class="nav nav-list">
        
        <!-- Bouton RSS -->
        <li class="nav-header">Flux et réseaux sociaux</li>
        <li><a href="<?php bloginfo('rss2_url'); ?>">S'abonner au flux RSS</a><li>
        
        <!-- Derniers articles -->
        <li class="nav-header">Derniers articles</li>
        <?php
        wp_reset_postdata();
        query_posts('posts_per_page=5');
        while (have_posts()) : the_post();
            ?>
            <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
        <?php endwhile; ?>
            
        <!-- Archives -->
        <li class="nav-header">Archives</li>
        <?php wp_get_archives('type=monthly'); ?>
        
    </ul>
   
</div>