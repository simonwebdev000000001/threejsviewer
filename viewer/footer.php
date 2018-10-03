<div>
<div class="copyright col-md-12">
    <p class="copyLine">© Copyright by Francfurt <?php echo date("Y");?>.</p>
    <ul id="menu-footer-agb" class="menu">
      
      <?php
          foreach ( $menuItems as $menuItem ){ 
            echo "<li class=\"menu-item menu-item-type-post_type menu-item-object-page menu-item-12454\">";
            echo "<a class=\"link ".strtolower($menuItem->title)."\" href=\"#!/".strtolower($menuItem->title)."\">".$menuItem->title."</a>";  
            echo"</li>";  
        } 
        ?>
</ul></div>

<style>
.player{
    bottom:20px;
}
.copyright {
    font-size: 10px!important;
    bottom: 0;
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
.copyright *{
    text-decoration: none;
    margin:0;
    color:white;
}
</style>
</div>