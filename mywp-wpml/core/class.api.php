<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if ( ! class_exists( 'MywpWPMLApi' ) ) :

final class MywpWPMLApi {

  private static $instance;

  private function __construct() {}

  public static function get_instance() {

    if ( !isset( self::$instance ) ) {

      self::$instance = new self();

    }

    return self::$instance;

  }

  private function __clone() {}

  private function __wakeup() {}

  public static function plugin_info() {

    $plugin_info = array(
      'document_url' => 'https://mywpcustomize.com/add_ons/add-on-wpml/',
      'website_url' => 'https://mywpcustomize.com/',
      'github' => 'https://github.com/gqevu6bsiz/mywp_addon_wpml',
      'github_tags' => 'https://api.github.com/repos/gqevu6bsiz/mywp_addon_wpml/tags',
    );

    $plugin_info = apply_filters( 'mywp_wpml_plugin_info' , $plugin_info );

    return $plugin_info;

  }

}

endif;
