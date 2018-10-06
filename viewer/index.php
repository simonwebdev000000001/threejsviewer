<?php

require_once(ABSPATH . 'wp-content/plugins/threejsviewer/config.php');
?>
<!DOCTYPE html>
<html>
<head>
    <base href="<?php echo get_site_url(); ?>/wp-content/plugins/threejsviewer/viewer/"/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">

    <title><?php echo $TITLE ?></title>
    <link rel="stylesheet" href="./assets/style.css?u" type="text/css" media="all">

    <script>
        let urlObj = location.href.split("?")[1],
            urlParams = window.urlParams = {};
        if (urlObj) urlObj = urlObj.split("&");
        if (urlObj) {
            for (let i = 0; i < urlObj.length; i++) {
                let _obj = urlObj[i].split("=");
                if (_obj.length > 1) {
                    this.urlParams[_obj[0]] = _obj[1];
                }
            }
        }
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelector('base').setAttribute('href', location.href);
        });
    </script>
</head>
<body>
<ul id="listOfSound" style="display:none;">
    <?php
    require_once(ABSPATH . 'wp-content/plugins/threejsviewer/loadFiles.php');
    $addedSomeFile = false;
    $items = load_files();
    foreach ($items as $_filePath) {
        $addedSomeFile = true;
        $fileName = explode("/", $_filePath);
        $fileName = explode(".", $fileName[count($fileName) - 1])[0];
        echo "<li data-title=" . $fileName . ">" . (get_site_url() . '/' . $_filePath) . "</li>";
    }


    $settings = load_settings();
    $isWindows = $isAllBuildings = $logoPath = -1;
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
                    $logoPath = $settItem->text;
                    $logoPath =
                        strpos($logoPath, 'http') === false ? ('/' . ($_SERVER['SERVER_NAME'] == 'localhost' ? 'wordpress/' : '') . $logoPath) : $logoPath;

                    break;
                }
            case 4:
                {
                    $hasGif = $val;
                    break;
                }
        }
    }
    ?>

</ul>
<script>
    <?php echo($isAllBuildings == 2 ? 'window.urlParams.all =true;' : '')?>
    <?php echo($isWindows == 2 ? 'window.urlParams.hasWindow =true;' : '')?>

    setTimeout(() => {
        <?php if ($hasGif == 1) echo "return;"?>
        function TextureAnimator(texture, tilesHoriz, tilesVert, numTiles, tileDispDuration) {
            // note: texture passed by reference, will be updated by the update function.

            this.tilesHorizontal = tilesHoriz;
            this.tilesVertical = tilesVert;
            // how many images does this spritesheet contain?
            //  usually equals tilesHoriz * tilesVert, but not necessarily,
            //  if there at blank tiles at the bottom of the spritesheet.
            this.numberOfTiles = numTiles;
            texture.wrapS = texture.wrapT = THREE.RepeatWrapping;
            texture.repeat.set(1 / this.tilesHorizontal, 1 / this.tilesVertical);

            // how long should each image be displayed?
            this.tileDisplayDuration = tileDispDuration;

            // how long has the current image been displayed?
            this.currentDisplayTime = 0;

            // which image is currently being displayed?
            this.currentTile = 0;

            this.update = function (milliSec) {
                this.currentDisplayTime += milliSec;
                while (this.currentDisplayTime > this.tileDisplayDuration) {
                    this.currentDisplayTime -= this.tileDisplayDuration;
                    this.currentTile++;
                    if (this.currentTile == this.numberOfTiles)
                        this.currentTile = 0;
                    var currentColumn = this.currentTile % this.tilesHorizontal;
                    texture.offset.x = currentColumn / this.tilesHorizontal;
                    var currentRow = Math.floor(this.currentTile / this.tilesHorizontal);
                    texture.offset.y = currentRow / this.tilesVertical;
                }
            };
        }

        var runnerTexture = new THREE.ImageUtils.loadTexture('wp-content/plugins/threejsviewer/viewer/assets/img/merge_from_ofoct.png');
        var annie = new TextureAnimator(runnerTexture, 6, 1, 6, 175); // texture, #horiz, #vert, #total, duration.
        var runnerMaterial = new THREE.MeshBasicMaterial({
            map: runnerTexture, transparent: true,
            side: THREE.DoubleSide
        });
        var runnerGeometry = new THREE.PlaneGeometry(5, 5, 1, 1);
        var runner = new THREE.Mesh(runnerGeometry, runnerMaterial);
        //runner.position.set(40,10,40);
        //runner.position.set(-100,25,0);
        var clock = new THREE.Clock();
        three.scene.add(runner);
        runner.position.set(0, 10, 0);
        //three.scene.children[0].children[232].add(runner);
        three.on('update', function () {
            var delta = clock.getDelta();

            annie.update(1000 * delta);
        });

    }, 5000);
</script>
<div class="canvas"></div>

<div class="splash">

    <?php
    echo '<img src="' . $logoPath . '"class=" "style="    height: 60px;
        margin-bottom: 70px;" alt="Bitfellas Analysis">'
    ?>
    <div class="sh" style="padding:10px;">a music and art disc for the web</div>
</div>

<div class="overlay">
    <div class="pause hide">
        <div></div>
    </div>
    <div class="play hide">
        <div></div>
    </div>
</div>

<?php
require 'header.php'; ?>

<div class="playlist"></div>

<div class="player box">

    <div class="flex">
        <div class="song">
            <div class="expand"></div>
        </div>

        <div class="progress">
            <div class="time current">0:00</div>
            <div class="scrubber">
                <div class="rail">
                    <div class="thumb">
                        <div class="sprite"></div>
                    </div>
                </div>
                <div class="bar">
                    <div class="load"></div>
                    <div class="fill"></div>
                </div>
            </div>
            <div class="time total">0:00</div>
        </div>

        <div class="controls">
            <div class="previous">
                <div></div>
                <div></div>
            </div>
            <div class="pause hide"></div>
            <div class="play"></div>
            <div class="next">
                <div></div>
                <div></div>
            </div>
            <div class="shuffle"></div>
        </div>

        <div class="volume">
            <div class="speaker">
                <div></div>
                <div></div>
                <div class="wave"></div>
            </div>

            <div class="scrubber">
                <div class="bar">
                    <div class="fill"></div>
                </div>
                <div class="rail">
                    <div class="thumb">
                        <div class="sprite"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="config">
            <div class="panel">
                <div class="flex">
                    <label class="fxaa" title="Fast Approximate Anti-Aliasing"><input type="checkbox"/> FXAA</label>
                    <label class="ssaa" title="Super-sampling level"><input type="range" min="0" max="5"/>
                        SSAA<span></span></label>
                    <br/>
                    <label class="fly" title="Dynamic fly-through camera"><input type="checkbox"/> Fly-through</label>
                    <label class="battery" title="Disable animation"><input type="checkbox"/> Battery Saver</label>
                </div>
            </div>
            <div class="gear">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <span></span></div>
        </div>

    </div>

</div>

<div class="content">
    <div class="wrap">


        <div class="scroll">

            <?php

            foreach ($menuLinks as $menuItem) {
                echo "
                  <section id=\"" . ($menuItem->link) . "\">
                  <div class=\"body\">" . stripslashes($menuItem->content) . "</div>
                </section>
                  ";
            }
            ?>
        </div>

        <div class="extra">
            <a href="#!/">
                <div class="close">
                    <div></div>
                    <div></div>
                </div>
            </a>
        </div>

    </div>
</div>

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
    <div class="close">
        <div></div>
        <div></div>
    </div>

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
<script type="text/javascript" src="build/bundle.js?u=<?php echo time() ?>"></script>


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
            setTimeout(function () {
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);
            }, 0);
        }
    }

    function exportToObj() {

        var exporter = new THREE.OBJExporter();
        var result = exporter.parse(three.scene.children[0]);
        //FileSave(result,'test.obj' );

        download(result, 'test.obj', 'text/*')
    }


</script>
</body>

<script type="text/javascript">(function () {
        window.switchTo5x = false;
        var e = document.createElement("script");
        e.type = "text/javascript";
        e.async = true;
        e.onload = function () {
            try {
                stLight.options({
                    publisher: "5c04738e-8606-4dbd-aed9-49b652099f7b-a51c",
                    doNotHash: true,
                    doNotCopy: true,
                    hashAddressBar: false,
                    onhover: false
                });
            } catch (e) {
            }
        };
        e.src = ("https:" == document.location.protocol ? "https://ws" : "https://ws") + ".sharethis.com/button/buttons.js";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(e, s);
    })();</script>
</html>
