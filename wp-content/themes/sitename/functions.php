<?php

include_once('functions/wordpress.php');

/**
 * Adiciona scripts e estilos
 * @author Armando Tomazzoni
 */
add_action('wp_enqueue_scripts', function () {
  wp_enqueue_style('css', get_stylesheet_directory_uri() . '/assets/dist/index.css', array(), '1.0.0');
  wp_enqueue_script('js', get_stylesheet_directory_uri() . '/assets/dist/index.js', array(), '1.0.0', true);
});
