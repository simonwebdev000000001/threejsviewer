<div>
    <div class="copyright col-md-12">
        <p class="copyLine">© Copyright by Francfurt <?php echo date("Y"); ?>.</p>
        <ul id="menu-footer-agb" class="menu menu-footer-agb">

            <?php
            foreach ($menuLinks as $menuItem) {
                if ($menuItem->general === true || $menuItem->isFooter === true) {
                    echo "<li class=\"menu-item menu-item-type-post_type menu-item-object-page menu-item-12454\">";
                   
                   if($menuItem->url){
                    echo "<a class=\"link\"  target=\"_blank\" href=\"" . $menuItem->url . "\">" . $menuItem->title . "</a>";
                   }else{
                    echo "<a class=\"link " . strtolower($menuItem->link) . "\" href=\"#!/" . strtolower($menuItem->link) . "\">" . $menuItem->title . "</a>";
                   }
                   
                    echo "</li>";
                }

            }
            ?>
        </ul>
    </div>

    <div style="display:none" id="listOfLinks">
        <?php
        foreach ($menuLinks as $menuItem) {
            if($menuItem->noContent)continue;
            echo "<a>" . $menuItem->link . "</a>";
        }
        ?>
    </div>

    <style>
        .player {
            bottom: 60px;
        }

        .copyright {
            font-size: 10px !important;
            bottom: 40px;
            position: fixed;
            min-width: 100%;
            height: 20px;
            z-index: 9;
            font-size: 13px;
            line-height: 20px;
            left: 0;
            text-align: center;
            color: rgb(255, 255, 255);
            display: flex;
            justify-content: center;
            background-color: rgb(0, 0, 0);
        }

        .menu-footer-agb {
            display: flex;
        }

        .menu-footer-agb > li {
            padding: 0 5px;
            list-style: none;
        }

        .copyright * {
            text-decoration: none;
            margin: 0;
            color: white;
        }
    </style>
</div>