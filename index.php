<!DOCTYPE html>
<html>
<head>
    <?php
        // Include web crawler file
        include 'simple_html_dom.php';

        // Set google drive folder location
        $dirname = 'https://www.googledrive.com/host/0B7fTCZWXVvWufnVwMnc3R2tBd3pySUZObmZsNU1lZmtUd0ZkbFdfTWhVYjRZSVlkakdTazQ/';

        $imgLinks = array();
        $imgTitles = array();

        // Retreive data from google drive
        $html = file_get_html($dirname);

        // Retreive title of google drive
        foreach ($html->find(".folder-name-header") as $title)
            $galleryTitle = $title->innertext;
    ?>

    <title><?php echo $galleryTitle; ?></title>

    <style type="text/css">

        img {
            width: 100%;
            height: auto;
        }

        html, body {
            width: 100%;
            height: 100%;
            margin: 0;
            background-color: #222222;
        }

        #title {
            width: 100%;
            height: 50px;
            margin-bottom: 1.5%;
            color: white;
            background-color: #3C3C3C;
            font-family: arial;
            font-weight: bold;
            font-size: 20pt;
            text-align: center;
        }

        #title-center {
            line-height: 2;
        }

        #image-slide {
            width: 25%;
            height: 100%;
            float: left;
            overflow-y: scroll;
            overflow-x: hidden;
        }

        #image-display {
            height: 100%;
            width: 70%;
            margin-left: 2%;
            float: left;
        }

        #displayImage {
        	display: block;
        	margin-left: auto;
        	margin-right: auto;
        }

        #inner-image {
            width: 100%;
            height: 100%;
        }

        .wrapper {
            width: 80%;
            height: 94%;
            position: relative;
            margin-right: auto;
            margin-left: auto;
            padding-top: 1.5%;
            overflow: hidden;
        }

        #rotate-button {
            z-index: 0;
            float: right;
            bottom: 0px;
            width: 50px;
            height: 20px;
        }

        .rotate90 {
             -webkit-transform: translateY(-100%)  rotate(90deg);
             -moz-transform: translateY(-100%)  rotate(90deg);
             -o-transform: translateY(-100%)  rotate(90deg);
             -ms-transform: translateY(-100%)  rotate(90deg);
             transform: translateY(-100%)  rotate(90deg);
             -webkit-transform-origin: bottom left;
             -moz-transform-origin: bottom left;
             -o-transform-origin: bottom left;
             -ms-transform-origin: bottom left;
             transform-origin: bottom left;
         }

        .rotate180 {
             -webkit-transform: rotate(180deg);
             -moz-transform: rotate(180deg);
             -o-transform: rotate(180deg);
             -ms-transform: rotate(180deg);
             transform: rotate(180deg);
         }

         .rotate270 {
             -webkit-transform: translateX(-100%) rotate(270deg);
             -moz-transform: translateX(-100%) rotate(270deg);
             -o-transform: translateX(-100%) rotate(270deg);
             -ms-transform: translateX(-100%) rotate(270deg);
             transform: translateX(-100%) rotate(270deg);
             -webkit-transform-origin: right top;
             -moz-transform-origin: right top;
             -o-transform-origin: right top;
             -ms-transform-origin: right top;
             transform-origin: right top;
         }

    </style>
</head>
<body>
	<?php
        // Retreive urls for all images on site
        foreach($html->find(".folder-content") as $mainList) {
            foreach ($mainList->find('a') as $e) {
                array_push($imgLinks, $dirname.$e->innertext);
                array_push($imgTitles, $e->innertext);
            }
        }
    ?>

    <div class="wrapper">
        <div id="image-slide"></div>
        <div id="image-display">
            <div id="inner-image">
                <div id="title">
                    <div id="title-center">
                        <?php echo $galleryTitle; ?>
                    </div>
                </div>
                <!-- <iframe width="100%" height="100%" src="https://906ebc9174f78d8aa259f124f0105c5e15f43299-www.googledrive.com/host/0B7fTCZWXVvWuflZqSWRLbXR4bUxiS3ZQdFBLTE5HTVVkZ2w0eFlKSnVraENobUtQa1BQVlU/1.JPG"></iframe> -->
                <div><img id="displayImage" src=""></div>
                <div id="rotatebox">
                    <button id="rotate-button" onclick="rotate(1)">-></button>
                    <button id="rotate-button" onclick="rotate(-1)"><-</button>
                </div>
            </div>
        </div>
    </div>

    <script src="exif.js"></script>

    <script type="text/javascript">

    	var isFirefox = typeof InstallTrigger !== 'undefined';
        var rot = 0;

        function changesrc(_src, w, h) {
            curImage = document.getElementById("displayImage");
            curImage.src = _src;
            if(isFirefox) {
            	if(w > h)
            		curImage.setAttribute("style", "height: auto; width: 100%");
            	else
            		curImage.setAttribute("style", "height: 100%; width: auto");
            }
            curImage.setAttribute("onclick", "window.open(this.src)");
            curImage.className = "";
            rot = 0;
        }

        function rotate(lr) {
            curImage = document.getElementById("displayImage");

            rot += lr;
            // console.log(rot);
            if(rot == -4 || rot == 4)
                rot = 0;

            if(rot == 0) {
                curImage.className = "";
            }
            else if(rot == 1 || rot == -3) {
                curImage.className = "rotate90";
            }
            else if(rot == 2 || rot == -2) {
                curImage.className = "rotate180";
            }
            else if(rot == 3 || rot == -1) {
                curImage.className = "rotate270";
            }

            console.log("W=" + curImage.width + " H=" + curImage.height);

            // if(curImage.width > curImage.height)
            //     curImage.setAttribute("style", "height: auto; width: 100%");
            // else
            //     curImage.setAttribute("style", "height: 100%; width: auto");
        }

        // Store array from php crawler in js array
        var imageLinks = <?php echo json_encode($imgLinks) ?>;
        var imageTitles = <?php echo json_encode($imgTitles) ?>;
        var preloadImages = [];

        x = 0;

        preloadAllImages(0);

        function preloadAllImages(x) {
            preloadImages[x] = new Image();
            if(x != imageLinks.length-1)
                preloadImages[x].setAttribute("style", "margin-bottom: 10px");
            preloadImages[x].setAttribute("onclick", "changesrc(this.src, this.width, this.height)");
            preloadImages[x].src = imageLinks[imageLinks.length - 1 - x];
            document.getElementById("image-slide").appendChild(preloadImages[x]);
            if(x < imageLinks.length-1) {
                preloadImages[x].onload = function() {
                    preloadAllImages(x+1);
                }
            }
        }

        if(preloadImages.length != 0) {
            changesrc(preloadImages[0].src, preloadImages[0].width, preloadImages[0].height);
        }

    </script>

</body>
</html>
