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
        add_action('wp_ajax_apply_content_to_wordpress_menu', 'apply_content_to_wordpress_menu');

        register_activation_hook(__FILE__, 'threejsviewer_activate');


        //add_action('parse_request', 'vm_parse_request');
        //add_filter( 'page_template', 'wpa3396_page_template' );
        // add_filter( 'init', 'wpa3396_page_template' );

        //add_action( 'wp_router_generate_routes', 'bl_add_routes', 20 );
        //add_action( 'admin_footer', 'my_action_javascript' ); // Write our JS below here

    }

}

require_once(ABSPATH . 'wp-content/plugins/threejsviewer/config.php');


function my_test_metabox()
{
    add_meta_box('my_test_metabox', 'File upload', 'my_test_metabox_out', 'post');
}


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

    $table_name = $wpdb->prefix . TABLES[0];

    $wpdb->query(
        'DELETE  FROM ' . $table_name . ' 
     WHERE id = "' . $myid . '"'
    );


    wp_die();
}

function apply_content_to_wordpress_menu()
{
    global $wpdb;
    $table_name = $wpdb->prefix . TABLES[0];
    $menuId = $_POST['menuId'];
    $content = $_POST['content'];
    $el = $wpdb->get_results('SELECT id from ' . $table_name . ' where menuId=' . $menuId, OBJECT);
    if (count($el) > 0) {
        $wpdb->query('UPDATE  ' . $table_name . ' SET content =\'' . $content . "' WHERE `menuId`=" . $menuId);
    } else {
        $wpdb->query('INSERT INTO ' . $table_name . ' (menuId, content) VALUES(' . $menuId . ', \'' . $content . '\')');
    }


    wp_die();
}

function my_toggleViewerSettings()
{
    global $wpdb;
    $table_name = $wpdb->prefix . TABLES[1];

    $wpdb->query('UPDATE ' . $table_name . ' SET value=' . $_POST['value'] . ' WHERE type=' . $_POST['type']);

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
    title varchar(55)  NULL,
    menuId mediumint(9) NULL,
    content text NOT NULL,
    PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    $wpdb->query('DROP TABLE IF EXISTS  ' . $table_name);
    dbDelta($sql);

    $table_name = $wpdb->prefix . TABLES[1];
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    createdAt datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
    type mediumint(9) NOT NULL,
    value mediumint(9) NOT NULL,
    text text   NULL,
    PRIMARY KEY  (id)
    ) $charset_collate;";

    $wpdb->query('DROP TABLE IF EXISTS  ' . $table_name);
    dbDelta($sql);

    //$wpdb->query( 'DELETE  FROM ' . $table_name  );
    $wpdb->query(
        'INSERT into ' . $table_name
        . " (`type`, `value`,`text`) Values(1,1,NULL),(2,1,NULL),(3,1,
        'https://hdlogo.files.wordpress.com/2018/02/eintracht-frankfurt-logo-png.png'),(4,1,NULL)"
    );


    //register_nav_menu('header-main-menu',__( 'Header Main Menu' ));
    //register_nav_menu('footer-extra-menu',__( 'Footer Extra Menu' ));

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
    if (is_page('my-custom-page-slug') || is_home()) {
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
    add_menu_page('Threejs Viewer Page', 'Threejs Viewer', 'manage_options', 'threejs-viewer-plugin', 'on_init_admin');
}


function on_init_admin()
{
    require_once(ABSPATH . 'wp-content/plugins/threejsviewer/loadFiles.php');
    require_once(ABSPATH . 'wp-content/plugins/threejsviewer/viewer/listOfMenuItems.php');

    test_handle_post();
    test_handle_post_test_upload_logo();

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
        <?php submit_button('Upload sounds') ?>
    </form>

    <hr/>
    <h2>Upload a logo, image only</h2>

    <!-- Form to handle the upload - The enctype value here is very important -->
    <form method="post" enctype="multipart/form-data">
        <input type='file' id='test_upload_logo' multiple accept="image/*" name='test_upload_logo'/>
        <?php submit_button('Upload logo') ?>
    </form>


    <?php
    $settings = load_settings();
    $isWindows = $isAllBuildings = -1;
    foreach ($settings as $settItem) {
        $val = $settItem->value;
        switch ($settItem->type) {
            case 1:
                {
                    $isAllBuildings = $val;
                    break;
                }
            case 2:
                {
                    $isWindows = $val;
                    break;
                }
            case 3:
                {
                    $logo = $settItem->text;
                    break;
                }
            case 4:
                {
                    $hasGif = $val;
                    break;
                }
        }
    }
    $path = strpos($logo, 'http') === false ? ('/' . ($_SERVER['SERVER_NAME'] == 'localhost' ? 'wordpress/' : '') . $logo) : $logo;
    echo '<h2>Current logo</h1></br><img src="' . ($path) . '" style="width:200px"/>';
    ?>
    <script>
        function changeSetting(type, e) {
            $.ajax({
                url: ajaxurl,
                data: {
                    'action': 'my_toggleViewerSettings',
                    'type': type,
                    'value': e.checked ? 2 : 1
                },
                method: 'POST',
                success: function (response) {
                },
                error: function (error) {
                    console.log(error)
                }
            });
        }
    </script>
    <hr/>
    <h2>Viewer Settings</h2>
    <form method="post" class="form-inline" enctype="multipart/form-data" name="settings_viewer">
        <div class="form-inline">
            <label class="sr-only" for="is_all_buildings"> See all buildings</label>
            <input class="form-control" <?php echo($isAllBuildings == 2 ? 'checked' : ''); ?>
                   type='checkbox' id="is_all_buildings" name='isAllBuildings' onclick="changeSetting(1,this)"/>
        </div>
        <div class="form-inline">
            <label class="sr-only" for="is_has_windows"> See with windows</label>
            <input class="form-control" <?php echo($isWindows == 2 ? 'checked' : ''); ?> type='checkbox'
                   id="is_has_windows" name='is_has_windows' onclick="changeSetting(2,this)"/>
        </div>
        <div class="form-inline">
            <label class="sr-only" for="is_has_gif"> See gif</label>
            <input class="form-control" <?php echo($hasGif == 2 ? 'checked' : ''); ?> type='checkbox'
                   id="is_has_gif" name='is_has_gif' onclick="changeSetting(4,this)"/>
        </div>

    </form>

    <hr/>
    <h3>Edit wordpress menu('Apply Content' button will apply all content from current edditor below)</h3>

    <?php

    $menus = get_registered_nav_menus();
    $menuActive = (wp_get_nav_menus());
    $menuLoc = (get_nav_menu_locations());
    $menuWordPressItems = load_Menu_Items(true);
    echo "<div><dl>";
    foreach ($menus as $key => $menu) {
        $id_m = $menuLoc[$key];
        $selectedMenu;
        foreach ($menuActive as $item) {
            if ($item->term_id == $id_m) {
                $selectedMenu = $item;
                break;
            }
        }
        echo "<dt>" . $key . "(" . $menu . ")</dt>";
        if (!$selectedMenu) {
            echo "<dd>You don`t have menu applied</dd>";
        } else {
            $args = array(
                'order' => 'ASC',
                'orderby' => 'menu_order',
                'output' => ARRAY_A,
                'output_key' => 'menu_order',
                'update_post_term_cache' => false,
            );
            $menuItems = wp_get_nav_menu_items($selectedMenu->term_id, $args);
            if (count($menuItems) == 0) {
                echo "<dd>You don`t hav any menu items attached</dd>";
            } else {
 
                foreach ($menuItems as $keyI => $menuI) {
                    $content = null;
                    foreach ($menuWordPressItems as $menuWP) {
                        if ($menuWP->menuId == $menuI->ID) {
                            $content = stripslashes($menuWP->content);
                            break;
                        }
                    }
                    echo "<dd><a href=\"". $menuI->url."\" target=\"_blank\" aria-label=\"Read more about Seminole tax hike\" >" . $menuI->title . "</a> <button onclick=\"openModalToEditMenuContent(" . $menuI->ID . ")\">Apply Content</button>";
                    if (!empty($content)) {
                        echo " <button onclick=updateContent(this)>View Content<div style=\"display:none\">" . $content . "</div></button>";
                    } else {
                        echo " <span>This menu item will use native link from worpress setting, apply some content if you want to use content instead of link</span>";
                    }
                    echo "</dd>";
                }
            }
        }

    }
    echo "</dl></div>";
    ?>
    <script>
        function updateContent(e) {
            tinymce.activeEditor.setContent(e.children[0].innerHTML);
        }

        function openModalToEditMenuContent(menuId) {
            let content = tinymce.activeEditor.getContent();
            if (!content) return alert('Please add some content in viewer');
            $.ajax({
                url: ajaxurl,//+"?action=my_action_javascript",
                data: {
                    'action': 'apply_content_to_wordpress_menu',
                    'content': content,
                    'menuId': menuId
                },
                method: 'POST',
                success: function (response) {
                    alert('Content was applied!!!');
                    location.reload();
                },
                error: function (error) {
                    console.log(error)
                }
            });
        }

    </script>


    <?php $menuItems = load_Menu_Items();
    if (count($menuItems) > 0) {
        echo "
            <hr/>
            <h3>List of General(Footer, Header) Menus</h3>
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
        <h3>Add new menu item(will be available on header and on footer)</h3>
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
            echo "File sound upload successful!";
        }
    }
}

function test_handle_post_test_upload_logo()
{
    // First check if the file appears on the _FILES array
    if (isset($_FILES['test_upload_logo'])) {
        $pdf = $_FILES['test_upload_logo'];

        // Use the wordpress function to upload
        // test_upload_pdf corresponds to the position in the $_FILES array
        // 0 means the content is not associated with any other posts
        $uploaded = media_handle_upload('test_upload_logo', 0);
        // Error checking using WP functions
        if (is_wp_error($uploaded)) {
            echo "Error uploading file: " . $uploaded->get_error_message();
        } else {
            $delimeter = 'wp-conten';
            $pathTofile = get_attached_file($uploaded);
            $path = $delimeter . explode($delimeter, $pathTofile)[1];
            global $wpdb;
            $table_name = $wpdb->prefix . TABLES[1];

            $wpdb->query('UPDATE ' . $table_name . ' SET text=\'' . $path . '\' WHERE type=3');
            echo "File logo upload successful!";
        }
    }
}


/*
 * Starts our plugin class, easy!
 */
new Threejsviewer();
