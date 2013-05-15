<!DOCTYPE html>

<html>
    <head <?php language_attributes(); ?>>
        <meta charset="<?php bloginfo('charset'); ?>" />
        <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
        <title><?php bloginfo('name'); ?></title>
        
        <?php wp_head(); ?>
        
        <link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/css/bootstrap.css" type="text/css" />
        <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" />
        
                
    </head>
    <body>
        <div class="container">
            
            <header class="hero-unit">
                <h1><a><?php bloginfo('name'); ?></a></h1>
                <h2><?php bloginfo('description'); ?></h2>
            </header>        