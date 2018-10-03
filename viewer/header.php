<?php ?>
<header class="flex">
    <h1 class="logo">
        <a href="#!">
            <?php require 'logo.php'; ?>
        </a>

    </h1>
    <nav class="flex" id="menu_items">

        <?php
        require_once(ABSPATH . 'wp-content/plugins/threejsviewer/viewer/listOfMenuItems.php');
        $menuItems = load_Menu_Items();
        $menuLinks = [];
        foreach ($menuItems as $key => $menuItem) {
            $title = $menuItem->title;
            $title = mb_convert_encoding($title, 'UTF-8', mb_detect_encoding($title));
            $title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); //The output should be ok, see the source code generated and search for the correct html entities for these special chars

            $link = preg_replace('/\s+/', '', mb_strtolower($title));
            $_link = 'view' . ($key + 1);// $link ;
            $menuLinks[] = (object)[
                'link' => $_link,
                'title' => $menuItem->title,
                'content' => $menuItem->content
            ];
            echo "<a class=\"link " . $_link . "\" data-item-link=\"" . $_link . "\" href=\"#!/" . $_link . "\">" . $menuItem->title . "</a>";
        }
        ?>
    </nav>
    <?php
    // require_once(ABSPATH . 'wp-admin/includes/plugin.php');

    // var_dump (get_plugins());
    //echo do_shortcode('[popup_anything id="56"]'); ?>
</header>