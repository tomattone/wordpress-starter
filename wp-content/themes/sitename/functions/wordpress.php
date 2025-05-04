<?php

/**
 * Habilita o title-tag
 * @author Armando Tomazzoni
 */
add_theme_support('title-tag');

/**
 * Habilita a imagem destacada
 * @author Armando Tomazzoni
 */
add_theme_support('post-thumbnails');

/**
 * Remove a tag de versão
 * @author Armando Tomazzoni
 */
remove_action('wp_head', 'wp_generator');

/**
 * Desabilita o Editor Gutenberg
 * @author Armando Tomazzoni
 */
add_filter('use_block_editor_for_post', '__return_false');

/**
 * Remove a toolbar no site quando está logado no admin
 * @author Armando Tomazzoni
 */
add_filter('show_admin_bar', '__return_false');

/**
 * Remove o estilo padrão do Gutenberg
 * @author Armando Tomazzoni
 */
add_action('wp_print_styles', 'wps_deregister_styles', 100);
function wps_deregister_styles() {
  wp_dequeue_style('wp-block-library');
}

/**
 * Remove alerta de atualizar wordpres
 * @author Armando Tomazzoni
 */
function wordpress_update_alert() {
  if (!current_user_can('publish_pages')) {
    remove_action('admin_notices', 'update_nag', 3);
  }
}
add_action('admin_menu', 'wordpress_update_alert');

/**
 * Remove a metabox de atributos da página
 * @author Armando Tomazzoni
 */
function page_attribute_support() {
  remove_post_type_support('page', 'page-attributes');
}
//add_action('init', 'page_attribute_support');

/**
 * Remove links da adminbar
 * @author Armando Tomazzoni
 */
function admin_bar_links() {
  global $wp_admin_bar;
  $wp_admin_bar->remove_menu('updates');
  $wp_admin_bar->remove_menu('comments');
  $wp_admin_bar->remove_menu('new-content');
}
add_action('wp_before_admin_bar_render', 'admin_bar_links');

/**
 * Remove links do menu principal (!= administrador)
 * @author Armando Tomazzoni
 */
function admin_menu_links() {

  remove_menu_page('edit-comments.php');
  remove_menu_page('profile.php');
  remove_menu_page('upload.php');
  remove_menu_page('link-manager.php');
  remove_menu_page('tools.php');
}
add_action('admin_menu', 'admin_menu_links');

/**
 * Remove widgets da dashboard
 * @author Armando Tomazzoni
 */
function default_dashboard_widgets() {
  remove_action('welcome_panel', 'wp_welcome_panel');
  remove_meta_box('dashboard_right_now', 'dashboard', 'core');
  remove_meta_box('dashboard_activity', 'dashboard', 'core');
  remove_meta_box('dashboard_recent_comments', 'dashboard', 'core');
  remove_meta_box('dashboard_incoming_links', 'dashboard', 'core');
  remove_meta_box('dashboard_plugins', 'dashboard', 'core');
  remove_meta_box('dashboard_quick_press', 'dashboard', 'core');
  remove_meta_box('dashboard_recent_drafts', 'dashboard', 'core');
  remove_meta_box('dashboard_primary', 'dashboard', 'core');
  remove_meta_box('dashboard_secondary', 'dashboard', 'core');
  remove_meta_box('dashboard_site_health', 'dashboard', 'core');
  remove_meta_box('sb_dashboard_widget', 'dashboard', 'normal');
}
add_action('admin_menu', 'default_dashboard_widgets');

/**
 * Permite o upload de SVG
 * @author Armando Tomazzoni
 */
add_filter(
  'upload_mimes',
  function ($upload_mimes) {
    // By default, only administrator users are allowed to add SVGs.
    // To enable more user types edit or comment the lines below but beware of
    // the security risks if you allow any user to upload SVG files.
    if (!current_user_can('administrator')) {
      return $upload_mimes;
    }

    $upload_mimes['svg']  = 'image/svg+xml';
    $upload_mimes['svgz'] = 'image/svg+xml';

    return $upload_mimes;
  }
);

/**
 * Add SVG files mime check.
 *
 * @param array        $wp_check_filetype_and_ext Values for the extension, mime type, and corrected filename.
 * @param string       $file Full path to the file.
 * @param string       $filename The name of the file (may differ from $file due to $file being in a tmp directory).
 * @param string[]     $mimes Array of mime types keyed by their file extension regex.
 * @param string|false $real_mime The actual mime type or false if the type cannot be determined.
 */
add_filter(
  'wp_check_filetype_and_ext',
  function ($wp_check_filetype_and_ext, $file, $filename, $mimes, $real_mime) {

    if (!$wp_check_filetype_and_ext['type']) {

      $check_filetype  = wp_check_filetype($filename, $mimes);
      $ext             = $check_filetype['ext'];
      $type            = $check_filetype['type'];
      $proper_filename = $filename;

      if ($type && 0 === strpos($type, 'image/') && 'svg' !== $ext) {
        $ext  = false;
        $type = false;
      }

      $wp_check_filetype_and_ext = compact('ext', 'type', 'proper_filename');
    }

    return $wp_check_filetype_and_ext;
  },
  10,
  5
);


/**
 * Corrige problema na acentuação das imagens
 * @author Armando Tomazzoni
 */
function hash_filename($filename) {
  $info = pathinfo($filename);
  $ext = empty($info['extension']) ? '' : '.' . $info['extension'];
  $name = basename($filename, $ext);
  return md5($name) . $ext;
}
add_filter('sanitize_file_name', 'hash_filename');

/**
 * Remover links da listagem (edição rápida, ver)
 * @author Armando Tomazzoni
 */
function disable_edit_actions($actions = array(), $post = null) {
  if (!current_user_can('publish_pages')) {
    if (isset($actions['inline hide-if-no-js'])) {
      unset($actions['inline hide-if-no-js']);
    }
    if (isset($actions['view'])) {
      unset($actions['view']);
    }
  }
  return $actions;
}
add_filter('post_row_actions', 'disable_edit_actions');
add_filter('page_row_actions', 'disable_edit_actions');

/**
 * Modifica o css da tela de login (/wp-login)
 * @author Armando Tomazzoni
 */
add_action('login_head', 'my_css_login');
function my_css_login() {
  echo '<style type="text/css">body{background:#fff}#login h1 a{background:url(' . get_template_directory_uri() . '/assets/img/logo.svg) no-repeat scroll center 0 transparent;width:100%;background-size:contain;height:90px}</style>';
}

/**
 * Modifica o css do painel do wordpress (/wp-admin)
 * @author Armando Tomazzoni
 */
add_action('admin_head', 'my_css_admin');
function my_css_admin() {
  echo '<style type="text/css">#wp-admin-bar-wp-logo{background:url(' . get_template_directory_uri() . '/assets/img/logo-white.svg) no-repeat scroll center center transparent!important;width:100px;height:43px;background-size:80% !important;margin:0 50px 0 10px!important;}#wp-admin-bar-wp-logo a,#wp-admin-bar-wp-logo div, .acf-to-rest-api-donation-notice,.ac-notice,.acf-will-escape{display:none!important}.acf-float-left{float:left!important;min-height:0!important}.acf-field-66aa2464b5859 img{background:#ccc !important}</style>';
}

/**
 * Remove a permisão de criação de páginas
 * @author Armando Tomazzoni
 */
function permissions_admin_redirect() {
  if (isset($_SERVER['REQUEST_URI'])) {
    $result = stripos($_SERVER['REQUEST_URI'], 'post-new.php?post_type=page');
    if ($result !== false && !current_user_can('publish_pages')) {
      wp_redirect(get_option('siteurl') . '/wp-admin/edit.php?post_type=page&permissions_error=true');
    }
  }
}
add_action('admin_menu', 'permissions_admin_redirect');

function permissions_admin_notice() {
  echo "<div id='permissions-warning' class='error fade'><p><strong>" . __('Você não tem permissão para criar novas páginas.') . "</strong></p></div>";
}

function permissions_show_notice() {
  if (isset($_GET['permissions_error']) && $_GET['permissions_error']) {
    add_action('admin_notices', 'permissions_admin_notice');
  }
}
add_action('admin_init', 'permissions_show_notice');

/**
 * Ajustes nos tipos de usuários
 * @author Armando Tomazzoni
 */
remove_role('file_uploader');
remove_role('contributor');
remove_role('subscriber');

/**
 * Personaliza o rodapé do wp-admin
 * @author Armando Tomazzoni
 */
add_filter('admin_footer_text', 'custom_admin_footer');
function custom_admin_footer() {
  echo '<div class="text" style="transition:all .2s ease;opacity:.7;display:flex;align-items:flex-start;justify-content:center;gap:4px">Site feito com 🧡 por <a target="_blank" href="https://tomazzoni.net?utm_source=client&amp;utm_medium=wordpress&amp;utm_campaign=rodoprima"><img src="https://tomazzoni.net/img/logo-horizontal.svg" height="18" style="position:relative;top:3.5px"></a></div>';
}

/**
 * Remove versão do footer
 * @author Armando Tomazzoni
 */
function footer_shh() {
  remove_filter('update_footer', 'core_update_footer');
}

add_action('admin_menu', 'footer_shh');

/**
 * Personaliza o tamanho do length do excerpt
 * @author Armando Tomazzoni
 */
add_filter('excerpt_length', 'custom_excerpt_length', 999);
function custom_excerpt_length($length) {
  return 22;
}

/**
 * Personaliza o leia mais do excerpt
 * @author Armando Tomazzoni
 */
add_filter('excerpt_more', 'new_excerpt_more');
function new_excerpt_more($more) {
  return '...';
}
