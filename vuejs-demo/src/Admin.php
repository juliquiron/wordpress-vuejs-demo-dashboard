<?php

/**
 * @file
 * Contains \Netzstrategen\VuejsDemo\Admin.
 */

namespace Netzstrategen\VuejsDemo;

/**
 * @brief Implements back-end administrative functionality.
 */
class Admin {

  /**
   * The parent admin menu used in 'add_submenu_page' function.
   */
  private static $parentAdminMenu = 'index.php';

  /**
   * Registers the options in the admin page for this plugin.
   *
   * @implements admin_init
   */
  static public function admin_init() {
  }

  /**
   * Creates the menu for this plugin.
   *
   * @implements admin_menu
   */
  static public function admin_menu() {
    add_submenu_page(
      self::$parentAdminMenu,
      __('VueDashboard', 'vuejsDemo'),
      __('VueDashboard', 'vuejsDemo'),
      'manage_options',
      'vuejsDemo',
      __CLASS__ . '::renderDashboard'
    );
  }

  /**
   * Renders options form for plugin main configuration page.
   */
  static public function renderDashboard() {
  }

  static public function appendScripts($hook) {
    if ($hook === 'dashboard_page_vuejsDemo') {
      wp_enqueue_style('material-fonts');
      wp_enqueue_style('veutify');
      wp_enqueue_style('vue-dashboard');

      wp_enqueue_script('vuejs');
      wp_enqueue_script('vuex');
      wp_enqueue_script('vuetify');
      wp_enqueue_script('vue-chartjs');
      wp_enqueue_script('vue-dashboard');
    }
  }

}
