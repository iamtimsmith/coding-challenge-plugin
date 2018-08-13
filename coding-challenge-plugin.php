<?php
/**
 * Plugin Name: Coding Challenge Plugin
 * Description: A custom Wordpress plugin built for a coding challenge.
 * Version: 1.0.0
 * Author: Tim Smith
 * Author URI: https://www.iamtimsmith.com
 */

 /**
  * Adds widget to admin dashboard
  */
  function coding_challenge_dashboard_app() {
    wp_add_dashboard_widget( 'coding_challenge_widget', 'Coding Challenge Widget', 'coding_challenge_dashboard' );
  }
  add_action('wp_dashboard_setup', 'coding_challenge_dashboard_app');

/**
 * Dashboard Layout
 */
function coding_challenge_dashboard() { ?>
  <div class="wrap">
    <h2>Imported Posts</h2>



  </div>

<?php }

/**
 * Add menu item for settings
 */
function coding_challenge_menu_item() {
  add_menu_page('Coding Challenge Settings', 'Coding Challenge', 'manage_options', 'coding-challenge-settings-page', 'coding_challenge_settings', 'dashicons-feedback');
}
add_action('admin_menu', 'coding_challenge_menu_item');


/**
 * Add page for plugin settings
 */
function coding_challenge_settings() { ?>
  <div class="wrap">
    <h2>Coding Challenge Settings</h2>
    <form action="">
      <div style="margin-bottom:20px"><label for="site-one">Site 1 URL: <input type="text" name="site-one" style="width:500px" required></label></div>
      <div style="margin-bottom:20px"><label for="site-two">Site 2 URL: <input type="text" name="site-two" style="width:500px"></label></div>
      <div style="margin-bottom:20px"><label for="site-three">Site 3 URL: <input type="text" name="site-three" style="width:500px"></label></div>
      <div style="margin-bottom:20px"><label for="site-four">Site 4 URL: <input type="text" name="site-four" style="width:500px"></label></div>
      <div style="margin-bottom:20px"><label for="site-five">Site 5 URL: <input type="text" name="site-five" style="width:500px"></label></div>
      <p></p>
      <hr>
      <p></p>
      <button class="button button-primary">Save Settings</button>
    </form>
  </div>

<?php }
  