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
    wp_register_style('material-fonts', 'https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons');
    wp_register_style('veutify', plugins_url("css/vuetify.min.css", dirname(__FILE__)) );
    wp_register_style('vue-dashboard', plugins_url('css/styles.css', dirname(__FILE__)));

    wp_register_script('vue-dashboard', plugins_url('js/script.js', dirname(__FILE__)), ['vuetify', 'vue-chartjs', 'vuex'], '1', TRUE);
    wp_register_script('vuetify', plugins_url('js/vuetify.min.js', dirname(__FILE__)), ['vuejs'], '', TRUE);
    wp_register_script('vue-chartjs', plugins_url('js/vue-chartjs.full.min.js', dirname(__FILE__)), ['vuejs'], '', TRUE);
    wp_register_script('vuejs', plugins_url("js/vue.js", dirname(__FILE__)), [], '', TRUE);
    wp_register_script('vuex', plugins_url("js/vuex.js", dirname(__FILE__)), ['vuejs'], '', TRUE);

    add_action("admin_enqueue_scripts", __CLASS__ . '::appendScripts');
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
    if (!current_user_can('manage_options')) {
      wp_die(__('You do not have sufficient permissions to access this page.', 'userLoginSessionsLimit'));
    }
    // TOP PRODUCTS
    $args = array(
      'post_type' => 'product',
      'post_status' => 'publish',
      'ignore_sticky_posts' => 1,
      'posts_per_page' => 10,
      'meta_key' => 'total_sales',
      'orderby' => 'meta_value_num',
      'meta_query' => array(
        array(
          'key' => '_visibility',
          'value' => array( 'catalog', 'visible'  ),
          'compare' => 'IN'
        )
      )
    );
    $products = new \WP_Query( $args  );
    if ($products->have_posts()) {
      $dashboardData = [];
      woocommerce_product_loop_start();
      while ($products->have_posts()) {
        $products->the_post();
        $postId = $products->post->ID;
        $dashboardData[$postId] = [
            'obj' => $products->post,
            'meta' => get_post_meta($postId),
            'brand' => wp_get_post_terms($postId, 'product_brand')[0]->name,
            'type' => wp_get_post_terms($postId, 'product_type')[0]->name,
        ];
      }
      woocommerce_product_loop_end();
      wp_reset_postdata();
      wp_localize_script('vue-dashboard', 'topProducts', $dashboardData);
    }
    // TOP by CATEGORIES
    $args = array(
      'post_type' => 'product',
      'post_status' => 'publish',
      'ignore_sticky_posts' => 1,
      'posts_per_page' => 10,
      'meta_key' => 'total_sales',
      'orderby' => 'meta_value_num',
      'meta_query' => array(
        array(
          'key' => '_visibility',
          'value' => array( 'catalog', 'visible'  ),
          'compare' => 'IN'
        )
      ),
      'tax_query' => array(
        array(
          'taxonomy' => 'product_brand',
          'field' => 'slug',
          'terms' => array('biohort', 'cane-line', 'glatz'),
          'operator' => 'IN'
        )
      )
    );
    $products = new \WP_Query( $args  );
    if ($products->have_posts()) {
      $dashboardData = [];
      woocommerce_product_loop_start();
      while ($products->have_posts()) {
        $products->the_post();
        $postId = $products->post->ID;
        $dashboardData[$postId] = [
            'obj' => $products->post,
            'meta' => get_post_meta($postId),
            'brand' => wp_get_post_terms($postId, 'product_brand')[0]->name,
            'type' => wp_get_post_terms($postId, 'product_type')[0]->name,
        ];
      }
      woocommerce_product_loop_end();
      wp_reset_postdata();
      wp_localize_script('vue-dashboard', 'brands', $dashboardData);
    }

    ?>
      <div class="wrap">
      <h1> <?php _e('User login sessions limit', 'userLoginSessionsLimit') ?></h1>
      <hr>
    <div id="app">
      <v-app>
        <main>
        <v-content>
          <v-container :fluid="true">
            <div id="getting-started" class='text-xs-center'>
              <v-row>
                <v-col xs6>
                  <v-card>
                    <v-toolbar class="white--text indigo">
                      <v-toolbar-title>Top products</v-toolbar-title>
                    </v-toolbar>
                    <v-list>
                      <v-list-item v-for="item in topProducts">
                        <v-list-tile avatar>
                          <v-list-tile-content>
                            <v-list-tile-title v-text="item.post_title" />
                          </v-list-tile-content>
                          <v-list-tile-avatar>
                            <span>{{ item.brand }}--{{ item.total_sales }}</span>
                          </v-list-tile-vatar>
                        </v-list-tile>
                      </v-list-item>
                    </v-list>
                  </v-card>
                </v-col>
                <v-col xs6>
                  <v-card>
                    <v-toolbar class="white--text blue">
                      <v-toolbar-title>Top for: Houe</v-toolbar-title>
                    </v-toolbar>
                    <v-list>
                      <v-list-item v-for="item in topProducts" v-if="item.brand === 'Houe'">
                        <v-list-title-content>
                          <v-list-tile-title v-text="item.post_title" />
                        </v-list-tile-content>
                      </v-list-item>
                    </v-list>
                  </v-card>

                  <v-card>
                    <v-toolbar class="white--text blue">
                      <v-toolbar-title>Top for: Kettler</v-toolbar-title>
                    </v-toolbar>
                    <v-list>
                      <v-list-item v-for="item in topProducts" v-if="item.brand === 'Kettler' || item.brand === 'Kettler HKS'">
                        <v-list-title-content>
                          <v-list-tile-title v-text="item.post_title" />
                        </v-list-tile-content>
                      </v-list-item>
                    </v-list>
                  </v-card>
                </v-col>
             </v-row>
            </div>
          </v-container>
        </v-content>
        </main>
      </v-app>
    </div>


      </div>
    <?php
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
