<?php
?>
<!DOCTYPE html>
<html>
  <head>
  <base href="<?php echo get_site_url();?>/wp-content/plugins/threejsviewer/viewer/"/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">

    <title>Demo</title>
    <link rel="stylesheet" href="./assets/style.css?u" type="text/css" media="all">

<script>
      let urlObj =  location.href.split("?")[1],
      urlParams =window.urlParams= {};
        if (urlObj)urlObj = urlObj.split("&");
        if (urlObj) {
            for (let i = 0; i < urlObj.length; i++) {
                let _obj = urlObj[i].split("=");
                if (_obj.length > 1) {
                    this.urlParams[_obj[0]] = _obj[1];
                }
            }
        }
        document.addEventListener("DOMContentLoaded", function(){
          document.querySelector('base').setAttribute('href',location.href);
        });
</script>
  </head>
  <body>
  <ul  id="listOfSound" style="display:none;">
  <?php
    require_once( ABSPATH . 'wp-content/plugins/threejsviewer/loadFiles.php' ); 
    $addedSomeFile =false;
    foreach ( load_files() as $_filePath ){
      $addedSomeFile =true;
      $fileName = explode("/",$_filePath);
      $fileName = explode(".", $fileName[count($fileName)-1])[0];
      echo "<li data-title=".$fileName.">".(get_site_url().'/'.$_filePath)."</li>";  
    }
    if(!$addedSomeFile){
      echo "<li data-title=\"Empty\">
      http://analysis.4sceners.de/assets/music/Absolute%20Valentine%20-%20Out%20of%20the%20Void.mp3</li>"; 
    }
  ?>
  </ul>
    <div class="canvas"></div>
    
    <div class="splash">

      <div class="sh">a music and art disc for the web</div>
    </div>
    
    <div class="overlay">
      <div class="pause hide"><div></div></div>
      <div class="play hide"><div></div></div>
    </div>

    <header class="flex">
      <h1 class="logo">
      	<a href="#!">
          <?php require 'logo.php'; ?>
        </a>
        
      </h1>
      <nav class="flex" id="menu_items">
        
      <?php
        require_once( ABSPATH . 'wp-content/plugins/threejsviewer/viewer/listOfMenuItems.php' );  
        $menuItems = load_Menu_Items();
        foreach ( $menuItems as $menuItem ){ 
          echo "<a class=\"link ".strtolower($menuItem->title)."\" href=\"#!/".strtolower($menuItem->title)."\">".$menuItem->title."</a>";  
        } 
      ?> 
      </nav>
    </header>

    <div class="playlist"></div>

    <div class="player box">

      <div class="flex">
        <div class="song">
          <div class="expand"></div>
        </div>

        <div class="progress">
          <div class="time current">0:00</div>
          <div class="scrubber">
            <div class="rail"><div class="thumb"><div class="sprite"></div></div></div>
            <div class="bar"><div class="load"></div><div class="fill"></div></div>
          </div>
          <div class="time total">0:00</div>
        </div>

        <div class="controls">
          <div class="previous"><div></div><div></div></div>
          <div class="pause hide"></div>
          <div class="play"></div>
          <div class="next"><div></div><div></div></div>
          <div class="shuffle"></div>
        </div>

        <div class="volume">
          <div class="speaker"><div></div><div></div><div class="wave"></div></div>

          <div class="scrubber">
            <div class="bar"><div class="fill"></div></div>
            <div class="rail"><div class="thumb"><div class="sprite"></div></div></div>
          </div>
        </div>
        
        <div class="config">
          <div class="panel">
            <div class="flex">
              <label class="fxaa" title="Fast Approximate Anti-Aliasing"><input type="checkbox" /> FXAA</label>
              <label class="ssaa" title="Super-sampling level"><input type="range" min="0" max="5" /> SSAA<span></span></label>
              <br />
              <label class="fly" title="Dynamic fly-through camera"><input type="checkbox" /> Fly-through</label>
              <label class="battery" title="Disable animation"><input type="checkbox" /> Battery Saver</label>
            </div>
          </div>
          <div class="gear"><div></div><div></div><div></div><div></div><div></div><span></span></div>
        </div>

      </div>

    </div>

    <div class="content">
    <div class="wrap">

      
      <div class="scroll"> 

        <?php
                 
                foreach ( $menuItems as $menuItem ){ 
                  echo "
                  <section id=\"".strtolower($menuItem->title)."\">
                  <div class=\"body\">".$menuItem->content."</div>
                </section>
                  ";
                    } 
              ?> 
      </div>

      <div class="extra">
        <a href="#!/">
          <div class="close"><div></div><div></div></div>
        </a>
      </div>

    </div></div>

    <div class="viewer">
        
      <div class="header">
        <strong class="title"></strong> <span class="artist"></span>
      </div>

      <div class="meta">
        
      </div>
      
      <a href="#!/graphics">
        <div class="canvas"></div>
      </a>
      
      <div class="prev"></div>
      <div class="next"></div>
      <div class="close"><div></div><div></div></div>

      <div class="preload before"></div>
      <div class="preload after"></div>
    </div>

    <div class="loader">
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
    </div>
 
<?php 
require 'coockies.php'; 
require 'footer.php'; 
?>
 <script type="text/javascript" src="build/loader.js?u"></script>
  <script type="text/javascript" src="build/bundle.js?u=<?php  echo time()?>"></script>   
 

<script>
function FileSave(sourceText, fileIdentity) {
    var workElement = document.createElement("a");
    if ('download' in workElement) {
        workElement.href = "data:" + 'text/plain' + "charset=utf-8," + escape(sourceText);
        workElement.setAttribute("download", fileIdentity);
        document.body.appendChild(workElement);
        var eventMouse = document.createEvent("MouseEvents");
        eventMouse.initMouseEvent("click", true, false, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
        workElement.dispatchEvent(eventMouse);
        document.body.removeChild(workElement);
    } else throw 'File saving not supported for this browser';
}
function download(data, filename, type) {
    var file = new Blob([data], {type: type});
    if (window.navigator.msSaveOrOpenBlob) // IE10+
        window.navigator.msSaveOrOpenBlob(file, filename);
    else { // Others
        var a = document.createElement("a"),
                url = URL.createObjectURL(file);
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        setTimeout(function() {
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);  
        }, 0); 
    }
}
	function exportToObj() {

				var exporter = new THREE.OBJExporter();
				var result = exporter.parse( three.scene.children[0]);
				//FileSave(result,'test.obj' );
				 
				download(result, 'test.obj', 'text/*')
			}


</script>
  </body>
 
  <script type="text/javascript">(function(){window.switchTo5x=false;var e=document.createElement("script");e.type="text/javascript";e.async=true;e.onload=function(){try{stLight.options({publisher: "5c04738e-8606-4dbd-aed9-49b652099f7b-a51c", doNotHash: true, doNotCopy: true, hashAddressBar: false, onhover: false});}catch(e){}};e.src=("https:" == document.location.protocol ? "https://ws" : "https://ws") + ".sharethis.com/button/buttons.js";var s = document.getElementsByTagName("script")[0];s.parentNode.insertBefore(e, s);})();</script>
</html>
