<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if( ! class_exists( 'MywpThirdpartyAbstractModule' ) ) {
  return false;
}

if ( ! class_exists( 'MywpWPMLThirdpartyModuleWPML' ) ) :

final class MywpWPMLThirdpartyModuleWPML extends MywpThirdpartyAbstractModule {

  protected static $id = 'wpml';

  protected static $base_name = 'sitepress-multilingual-cms/sitepress.php';

  protected static $name = 'WPML';

  public static function after_init() {

    add_filter( 'mywp_shortcode' , array( __CLASS__ , 'mywp_shortcode' ) );

  }

  public static function mywp_init() {

    add_action( 'mywp_request_admin' , array( __CLASS__ , 'mywp_request_admin' ) );

    add_filter( 'mywp_setting_admin_posts_get_available_list_columns' , array( __CLASS__ , 'mywp_setting_admin_posts_get_available_list_columns' ) , 10 , 2 );

  }

  public static function mywp_shortcode( $shortcodes ) {

    $shortcodes['mywp_wpml_admin_column_flag'] = array( __CLASS__ , 'do_shortcode_admin_column_flag' );

    return $shortcodes;

  }

  public static function do_shortcode_admin_column_flag( $atts = array() , $content = false , $tag ) {

    global $sitepress;

    if( empty( $sitepress ) ) {

      return $content;

    }

    if( ! is_object( $sitepress ) ) {

      return $content;

    }

    $active_langs = $sitepress->get_active_languages();

    $active_languages = apply_filters( 'wpml_active_languages_access' , $active_langs , array( 'action' => 'edit' ) );

    if( count( $active_languages ) <= 1 || 'trash' === get_query_var( 'post_status' ) ) {

      return $content;

    }

    $current_language = $sitepress->get_current_language();

    if( isset( $active_languages[ $current_language ] ) ) {

      unset( $active_languages[ $current_language ] );

    }

    if( count( $active_languages ) > 0 ) {

      foreach ( $active_languages as $language_data ) {

        $flag_url = $sitepress->get_flag_url( $language_data['code'] );

        $content .= sprintf( '<img src="%1$s" width="18" height="12" alt="%2$s" title="%2$s" style="margin:2px" />' , esc_url( $flag_url ) , esc_attr( $language_data['display_name'] ) );

      }

    }

    return $content;

  }

  public static function mywp_request_admin() {

    global $sitepress;

    if( empty( $sitepress ) ) {

      return false;

    }

    if( ! is_object( $sitepress ) ) {

      return false;

    }

    $custom_posts_sync_option = $sitepress->get_setting( 'custom_posts_sync_option' );

    if( ! empty( $custom_posts_sync_option ) ) {

      foreach( $custom_posts_sync_option as $post_type => $setting ) {

        if( (int) $setting < 1 ) {

          continue;

        }

        add_filter( 'mywp_model_change_get_setting_data_mywp_admin_posts_' . $post_type , array( __CLASS__ , 'mywp_model_change_get_setting_data_mywp_admin_posts' ) , 10 , 3 );

      }

    }

  }

  public static function mywp_model_change_get_setting_data_mywp_admin_posts( $setting_data , $type , $is_network ) {

    if( $type !== 'controller' ) {

      return $setting_data;

    }

    if( ! isset( $setting_data['list_columns']['icl_translations'] ) ) {

      return $setting_data;

    }

    if( 'trash' === get_query_var( 'post_status' ) ) {

      unset( $setting_data['list_columns']['icl_translations'] );

    }

    return $setting_data;

  }

  public static function current_pre_plugin_activate( $is_plugin_activate ) {

    if( defined( 'ICL_SITEPRESS_VERSION' ) ) {

      return true;

    }

    return $is_plugin_activate;

  }

  public static function mywp_setting_admin_posts_get_available_list_columns( $available_list_columns , $list_column_id ) {

    if( isset( $available_list_columns['other']['columns']['icl_translations'] ) ) {

      $available_list_columns['other']['columns']['icl_translations']['width'] = 'auto';
      $available_list_columns['other']['columns']['icl_translations']['title'] = '[mywp_wpml_admin_column_flag]';
      $available_list_columns['other']['columns']['icl_translations']['default_title'] = '[mywp_wpml_admin_column_flag]';

    }

    return $available_list_columns;

  }

}

MywpWPMLThirdpartyModuleWPML::init();

endif;
