<?php $class = $args['class']; ?>
<!DOCTYPE html>
<html lang="pt-br" class="scroll-smooth">

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/assets/img/favicon.svg" />

  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>