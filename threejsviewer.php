<?php
/**
 * Plugin Name:       threejsviewer
 * Description:       3d viewer with threejs!
 * Version:           1.0.0
 * Author:            Siomask
 * Author URI:
 * Text Domain:       threejsviewer
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * GitHub Plugin URI: https://github.com/simonwebdev000000001/threejsviewer
 */

/*
 * Plugin constants
 */
if (!defined('FEEDIER_URL'))
    define('FEEDIER_URL', plugin_dir_url(__FILE__));
if (!defined('FEEDIER_PATH'))
    define('FEEDIER_PATH', plugin_dir_path(__FILE__));

/*
 * Main class
 */

/**
 * Class Threejsviewer
 *
 * This class creates the option page and add the web app script
 */
class Threejsviewer
{

    /**
     * Threejsviewer constructor.
     *
     * The main plugin actions registered for WordPress
     */
    public function __construct()
    {
        add_action('admin_menu', 'test_plugin_setup_menu');
        add_filter('template_include', 'wpa3396_page_template', 99);
        add_action('admin_print_footer_scripts', 'my_action_javascript', 99);

        add_action('wp_ajax_my_ajax_action', 'my_ajax_action');
        add_action('wp_ajax_delete_menu_item', 'delete_menu_item');
        add_action('wp_ajax_my_toggleViewerSettings', 'my_toggleViewerSettings');

        register_activation_hook(__FILE__, 'threejsviewer_activate');


        //add_action('parse_request', 'vm_parse_request');
        //add_filter( 'page_template', 'wpa3396_page_template' );
        // add_filter( 'init', 'wpa3396_page_template' );

        //add_action( 'wp_router_generate_routes', 'bl_add_routes', 20 );
        //add_action( 'admin_footer', 'my_action_javascript' ); // Write our JS below here

    }

}

require_once(ABSPATH . 'wp-content/plugins/threejsviewer/config.php');

function my_ajax_action()
{
    global $wpdb; // this is how you get access to the database
    $table_name = $wpdb->prefix . TABLES[0];

    ($wpdb->insert(
        $table_name,
        array(
            'title' => $_POST['title'],
            'content' => $_POST['content']
        )
    ));


    wp_die();
}

function delete_menu_item()
{
    global $wpdb; // this is how you get access to the database

    $myid = ($_POST['id']);

    $table_name = $wpdb->prefix . Threejsviewer::$TABLE_NAME;

    $wpdb->query(
        'DELETE  FROM ' . $table_name . ' 
     WHERE id = "' . $myid . '"'
    );


    wp_die();
}
function my_toggleViewerSettings(){
    global $wpdb; 
    $table_name = $wpdb->prefix . TABLES[1];

    $wpdb->query('UPDATE '.$table_name.' SET value='.$_POST['value'].' WHERE type='.$_POST['type']);
 
    wp_die();
}
function my_action_javascript()
{ ?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {

            $('#create_menu_submit').click(function (e) {
                var x = document.forms["create_menu"]["menu_title"].value;
                if (x == "") {
                    alert("Tirle must be filled out");
                    return false;
                }

                $.ajax({
                    url: ajaxurl,//+"?action=my_action_javascript",
                    data: {
                        'action': 'my_ajax_action',
                        'title': x,
                        'content': tinymce.activeEditor.getContent()
                    },
                    method: 'POST',
                    success: function (response) {
                        location.reload();
                    },
                    error: function (error) {
                        console.log(error)
                    }
                });
                return false;

            });

        });
    </script> <?php
}

function threejsviewer_activate()
{
    global $wpdb;

    $table_name = $wpdb->prefix . TABLES[0];

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    createdAt datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
    title varchar(55) NOT NULL,
    content text NOT NULL,
    PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    $table_name = $wpdb->prefix . TABLES[1]; 
    $charset_collate = $wpdb->get_charset_collate(); 
    $sql = "CREATE TABLE $table_name (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    createdAt datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
    type mediumint(9) NOT NULL,
    value mediumint(9) NOT NULL,
    PRIMARY KEY  (id)
    ) $charset_collate;";
 
    dbDelta($sql);
      
    $wpdb->query(
        'DELETE  FROM ' . $table_name  
    );
    $wpdb->query(
        'INSERT into '.$table_name.' (`type`, `value`) Values(1,1),(2,1)'
    );  
}

function bl_add_routes($router)
{
    $route_args = array(
        'path' => '^new-demo-route',
        'query_vars' => array(),
        'page_callback' => 'bl_new_demo_route_callback',
        'page_arguments' => array(),
        'access_callback' => true,
        'title' => __('Demo Route'),
        'template' => array(
            'viewer.php',
            dirname(__FILE__) . '/viewer/index.php'
        )
    );

    $router->add_route('demo-route-id', $route_args);
}

function bl_new_demo_route_callback()
{
    return "Congrats! Your demo callback is fully functional. Now make it do something fancy";
}


function wpa3396_page_template($page_template)
{
    if (is_page('my-custom-page-slug') || true) {
        $page_template = dirname(__FILE__) . '/viewer/index.php';
    }
    return $page_template;
}

function vm_parse_request(&$wp)
{
    global $wp;
    var_dump(($wp->request));
    if (empty($wp->query_vars['pagename']))
        return; // page isn't permalink

    $p = $wp->query_vars['pagename'];


    if (!preg_match("#viewer/([^/]+)#", $p, $m))
        return;

    // setup hooks and filters to generate virtual movie page
    add_action('template_redirect', array(&$this, 'vm_template_redir'));
    add_filter('the_content', array(&$this, 'vm_the_content'));

}

function vm_template_redir()
{
    // Reset currrently set 404 flag as this is a plugin-generated page
    global $wp_query;
    $wp_query->is_404 = false;

    $template = 'page.php';

    include("/viewer/index.php"); // child

    exit;
}


function vm_the_content($content)
{
    return "my new content (actually generated dynamically)";
}

function test_plugin_setup_menu()
{
    add_menu_page('Threejs Viewer Page', 'Threejs Viewer', 'manage_options', 'threejs-viewer-plugin', 'test_init');
}


function test_init()
{
    require_once(ABSPATH . 'wp-content/plugins/threejsviewer/loadFiles.php');
    require_once(ABSPATH . 'wp-content/plugins/threejsviewer/viewer/listOfMenuItems.php');

    test_handle_post();

    $soundFiles = load_files();
    if (count($soundFiles) > 0) {
        echo "
        <hr/>
        <h3>List of Sound Files</h3>
        <ul =\"listofSounds\">
    ";

        foreach ($soundFiles as $_filePath) {
            $fileName = explode("/", $_filePath);
            $fileName = $fileName[count($fileName) - 1];
            echo "<li>" . $fileName . "</li>";
        }
        echo "</ul>";
    }

    ?>
    <hr/>
    <h2>Upload a sound file(s),audio only</h2>
    <!-- Form to handle the upload - The enctype value here is very important -->
    <form method="post" enctype="multipart/form-data">
        <input type='file' id='test_upload_sound' multiple accept="audio/*" name='test_upload_sound'/>
        <?php submit_button('Upload') ?>
    </form>

    <?php
     $settings = load_settings();
     $isWindows = $isAllBuildings=-1;
     foreach($settings as $settItem){
         $val = $settItem->value;
         switch($settItem->type){
             case 1:{
                $isAllBuildings = $val;
                 break;
             }
             case 2:{
                $isWindows = $val;
                 break;
             }
         } 
     }


    ?> 
    <script>
        function changeSetting(type,e){
            $.ajax({
                    url : ajaxurl, 
                    data : {
                        'action':'my_toggleViewerSettings',
                        'type': type,
                        'value':e.checked?2:1
                    },
                    method : 'POST',
                    success : function( response ){  },
                    error : function(error){ console.log(error) }
                }) ; 
        }
    </script>
     <hr/>
    <h2>Viewer Settings</h2>
     <form method="post"class="form-inline" enctype="multipart/form-data" name="settings_viewer">
        <div class="form-inline">
            <label  class="sr-only"for="is_all_buildings"> See all buildings</label>
            <input class="form-control" <?php echo ($isAllBuildings==2?'checked':'');?> 
            type='checkbox' id="is_all_buildings" name='isAllBuildings' onclick="changeSetting(1,this)"/> 
        </div>
        <div class="form-inline">
            <label  class="sr-only"for="is_has_windows"> See with windows</label>
            <input class="form-control" <?php echo ($isWindows==2?'checked':'');?> type='checkbox'
             id="is_has_windows" name='is_has_windows' onclick="changeSetting(2,this)"/> 
        </div>
            
    </form>
    
    <?php $menuItems = load_Menu_Items();
    if (count($menuItems) > 0) {
        echo "
            <hr/>
            <h3>List of Menus</h3>
            <ul>
        ";
        foreach ($menuItems as $item) {
            echo "<li>" . $item->title . "<button data-delete-menu=" . $item->id . ">Delete</button></li>";
        }
        echo "</ul>";
    }
    echo "
    <script>
        document.addEventListener(\"DOMContentLoaded\", function(){
            $('button[data-delete-menu]').click(function(e){
                $.ajax({
                    url : ajaxurl, 
                    data : {
                        'action':'delete_menu_item',
                        'id': $(e.target).data('delete-menu')
                    },
                    method : 'POST',
                    success : function( response ){location.reload(); },
                    error : function(error){ console.log(error) }
                }) ;
            })
        });
        
    </script>
    "
    ?>

    <hr/>
    <form id="create_menu" name="create_menu" method="post" enctype="multipart/form-data">
        <h3>Add new menu item</h3>
        <input type="submit" value="Create" id='create_menu_submit'>
        <input id='menu_title' placeholder="title" requiured name='menu_title'/>

        <p>Input your content bellow for new menu item</p>
        <?php wp_editor('', 'my_threejs_Editor', $settings = array()); ?>
    </form>


    <?php
}

function test_handle_post()
{
    // First check if the file appears on the _FILES array
    if (isset($_FILES['test_upload_sound'])) {
        $pdf = $_FILES['test_upload_sound'];

        // Use the wordpress function to upload
        // test_upload_pdf corresponds to the position in the $_FILES array
        // 0 means the content is not associated with any other posts
        $uploaded = media_handle_upload('test_upload_sound', 0);
        // Error checking using WP functions
        if (is_wp_error($uploaded)) {
            echo "Error uploading file: " . $uploaded->get_error_message();
        } else {
            echo "File upload successful!";
        }
    }
}


/*
 * Starts our plugin class, easy!
 */
new Threejsviewer();
