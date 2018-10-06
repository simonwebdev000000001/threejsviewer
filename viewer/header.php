<?php ?>
<header class="flex">
    <h1 class="logo">
        <a href="#!">
            <?php
            echo '<img src="' . $logoPath . '"class="logo-item" alt="Bitfellas Analysis">'
            ?>

        </a>

    </h1>
    <nav class="flex" id="menu_items">

        <?php
        require_once(ABSPATH . 'wp-content/plugins/threejsviewer/viewer/listOfMenuItems.php');
        $menuItems = load_Menu_Items();
        $menuLinks = [];
        $menuItemsCounter = 0;
        foreach ($menuItems as $key => $menuItem) {
            $title = $menuItem->title;
            $title = mb_convert_encoding($title, 'UTF-8', mb_detect_encoding($title));
            $title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); //The output should be ok, see the source code generated and search for the correct html entities for these special chars

            $link = preg_replace('/\s+/', '', mb_strtolower($title));
            $_link = 'view' . ($menuItemsCounter++ + 1);// $link ;
            $menuLinks[] = (object)[
                'general' => true,
                'link' => $_link,
                'title' => $menuItem->title,
                'content' => $menuItem->content
            ];
            echo "<a class=\"link " . $_link . "\" data-item-link=\"" . $_link . "\" href=\"#!/" . $_link . "\">" . $menuItem->title . "</a>";
        }


        $menus = get_registered_nav_menus();
        $menuActive = (wp_get_nav_menus());
        $menuLoc = (get_nav_menu_locations());
        $menuWordPressItems = load_Menu_Items(true);
        foreach ($menus as $key => $menu) {

            $id_m = $menuLoc[$key];
            $selectedMenu;
            foreach ($menuActive as $item) {
                if ($item->term_id == $id_m) {
                    $selectedMenu = $item;
                    break;
                }
            }
            if (!$selectedMenu) {

            } else {
                $args = array(
                    'order' => 'ASC',
                    'orderby' => 'menu_order',
                    'output' => ARRAY_A,
                    'output_key' => 'menu_order',
                    'update_post_term_cache' => false,
                );
                $menuNavItems = wp_get_nav_menu_items($selectedMenu->term_id, $args);
                if (count($menuNavItems) == 0) {

                } else {
                    foreach ($menuNavItems as $keyI => $menuI) {
                        foreach ($menuWordPressItems as $menuWP) {
                            if ($menuWP->menuId == $menuI->ID) {
                                $_link = 'view' . ($menuItemsCounter++ + 1);// $link ;
                                $menuLinks[] = (object)[
                                    'isFooter' => $key == 'social',
                                    'link' => $_link,
                                    'title' => $menuI->title,
                                    'content' => $menuWP->content
                                ];
                                if ($key == 'top') {
                                    echo "<a class=\"link " . $_link . "\" data-item-link=\"" . $_link . "\" href=\"#!/" . $_link . "\">" . $menuI->title . "</a>";

                                }

                            }
                        }

                    }
                }
            }
        }
        ?>
    </nav>
    <?php

    // require_once(ABSPATH . 'wp-admin/includes/plugin.php');

    // var_dump (get_plugins());
    //echo do_shortcode('[popup_anything id="56"]'); ?>
</header>
<style>
    .logo > a {
        display: flex;
        justify-content: center;
    }

    .logo-item {
        max-width: 200px;
        padding: 0 20px;
        height: 55px;
    }

    header h1.logo {
        width: inherit;
        min-width: inherit;
        max-width: 213px;
    }
</style>