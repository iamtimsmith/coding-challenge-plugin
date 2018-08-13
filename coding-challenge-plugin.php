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
    <table>
      <tr>
        <th>Title</th>
        <th>Source</th>
        <th>Date</th>
      </tr>
    <?php
    $url_for_posts = [
      "http://" . parse_url( get_option('cc_site_one') )['host'],
      "http://" . parse_url( get_option('cc_site_two') )['host'],
      "http://" . parse_url( get_option('cc_site_three') )['host'],
      "http://" . parse_url( get_option('cc_site_four') )['host'],
      "http://" . parse_url( get_option('cc_site_five') )['host']
    ];
    $list_of_posts = [];

    foreach ($url_for_posts as $url) {
      $types = ['posts', 'pages'];
      foreach ($types as $type) {
        $body = wp_remote_retrieve_body( wp_remote_get("$url/wp-json/wp/v2/$type") );
        $results = json_decode($body);
        foreach ($results as $result) {
          if (!post_exists($result->title->rendered) ) {
            array_push($list_of_posts, $result);
          }
        }
      }
    }


    // Sort by date
    function sort_posts_by_date($a, $b) {
      if( strtotime($a->modified) == strtotime($b->modified) ){ return 0;}
      return ( strtotime($a->modified) > strtotime($b->modified)) ? -1 : 1;
    }
    usort($list_of_posts, 'sort_posts_by_date');

    // Add post submit
    if (array_key_exists('add_foreign_post', $_POST)) {
      $post_to_add = array(
        'post_title' => $_POST['foreign_post_title'],
        'post_content' => $_POST['foreign_post_content'],
        'post_type' => $_POST['foreign_post_type']
      );
      $post_id = wp_insert_post($post_to_add);
      $post = get_post($post_id);
      wp_redirect("/wp-admin/post.php?post=" . $post_id . "&action=edit");
    }

    foreach (array_slice($list_of_posts, 0, 10) as $post) {
      $render = "<tr>";
      $render .= "<td>" . $post->title->rendered ."</td>";
      $render .= "<td>" . parse_url($post->guid->rendered)['host'] ."</td>";
      $render .= "<td>" . $post->modified . "</td>";
      $render .= "<td><form method='post' action=''>";
      $render .= "<input style='display:none' type='text' name='foreign_post_title' value='" . $post->title->rendered . "' />";
      $render .= "<input style='display:none' type='text' name='foreign_post_type' value='" . $post->type . "' />";
      $render .= "<textarea name='foreign_post_content' style='display:none'>". $post->content->rendered ."</textarea>";
      $render .= "<input type='submit' name='add_foreign_post' class='button button-primary' value='Add' />";
      $render .= "</form></td>";
      $render .= "</tr>";
      echo $render;
    }

    ?>
    </table>
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
function coding_challenge_settings() { 
  
  if(array_key_exists('submit_cc_settings', $_POST)) {
    update_option('cc_site_one', $_POST['site-one'] );
    update_option('cc_site_two', $_POST['site-two'] );
    update_option('cc_site_three', $_POST['site-three'] );
    update_option('cc_site_four', $_POST['site-four'] );
    update_option('cc_site_five', $_POST['site-five'] );

    echo "<div id='setting-update' class='updated settings-error notice is-dismissible'><strong>Settings have been saved.</strong></div>";
  }
  
  // Get values
  $site1 = get_option('cc_site_one', '');
  $site2 = get_option('cc_site_two', '');
  $site3 = get_option('cc_site_three', '');
  $site4 = get_option('cc_site_four', '');
  $site5 = get_option('cc_site_five', '');
  
  ?>

  <div class="wrap">
    <h2>Coding Challenge Settings</h2>
    <form action="" method="post">
      <p style="color:#888; font-style:italic">URLs should be entered with no trailing slash. Sites must be Wordpress sites to function properly.</p>
      <div style="margin-bottom:20px"><label for="site-one">Site 1 URL: <input type="text" name="site-one" style="width:500px" value="<?php echo $site1; ?>"></label></div>
      <div style="margin-bottom:20px"><label for="site-two">Site 2 URL: <input type="text" name="site-two" style="width:500px"value="<?php echo $site2; ?>"></label></div>
      <div style="margin-bottom:20px"><label for="site-three">Site 3 URL: <input type="text" name="site-three" style="width:500px"value="<?php echo $site3; ?>"></label></div>
      <div style="margin-bottom:20px"><label for="site-four">Site 4 URL: <input type="text" name="site-four" style="width:500px"value="<?php echo $site4; ?>"></label></div>
      <div style="margin-bottom:20px"><label for="site-five">Site 5 URL: <input type="text" name="site-five" style="width:500px"value="<?php echo $site5; ?>"></label></div>
      <p></p>
      <hr>
      <p></p>
      <input type="submit" name="submit_cc_settings" class="button button-primary" value="Save Settings"/>
    </form>
  </div>

<?php }
  