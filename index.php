<!DOCTYPE html>
<html>
<head>
    <?php
        // Include web crawler file
        include 'simple_html_dom.php';

        // Set google drive folder location
        $dirname = 'https://www.googledrive.com/host/0B7fTCZWXVvWufmNXZmZGYVRyTHdRY0hhYzNxRV9MT0xXcEJsN04wVm5iM01JTkhYT1JFdk0/';
        // $dirname = 'https://www.googledrive.com/host/0B7fTCZWXVvWuflZqSWRLbXR4bUxiS3ZQdFBLTE5HTVVkZ2w0eFlKSnVraENobUtQa1BQVlU/';

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
            /*padding: 10px; */
            /*border: solid 1px #D5D5D5;*/
            /*margin-bottom: 10px;*/
        }

        html, body {
            width: 100%;
            height: 100%;
            margin: 0;
        }

        #image-title {
            width: 100%;
            height: 10%;
            float: right;
            /*min-height: 50px;*/
            /*padding: 15px 0px 15px  0px;*/
            text-align: center;
            font-size: 175%;
            font-family: arial;
            /*color: white;*/
        }

        #image-slide {
            width: 25%;
            height: 100%;
            /*position: absolute;*/
            /*height: auto; */
            float: left;
            overflow-y: scroll;
            overflow-x: hidden;
        }

        #display-buffer-top {
            height: 5%;
            width: 70%;
            float: left;
            text-align: center;
            font-family: arial;
            font-size: 150%;
            padding-top: 10px;
        }

        #display-buffer-right {
            height: 5%;
            width: 5%;
            float: left;  
        }

        #image-display {
            /*position: fixed;*/
            height: 80%;
            width: 70%;
            /*padding: 20px;*/
            float: left;  
        }

        #inner-image {
            width: 100%;
            height: 100%;
            /*margin: 10px;*/
        }

        .wrapper {
            width: 80%;
            height: 100%;
            position: relative;
            margin-right: auto;
            margin-left: auto;
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
        <div id="display-buffer-right"></div>
        <div id="display-buffer-top"><?php echo $galleryTitle; ?></div>
        <div id="display-buffer-right"></div>
        <div id="image-display">
            <div id="inner-image">
                <img id="displayImage" src="">
            </div>
        </div>
    </div>
    
    <script type="text/javascript">

        function changesrc(_src) {
            document.getElementById("displayImage").src = _src;
            // document.getElementById("")
        }

        // Store array from php crawler in js array
        var imageLinks = <?php echo json_encode($imgLinks) ?>;
        var imageTitles = <?php echo json_encode($imgTitles) ?>;
        var preloadImages = [];

        for(var x = 0; x < imageLinks.length; x++) {
            preloadImages[x] = new Image();
            if(x == 0)
                preloadImages[x].setAttribute("style", "margin: 10px 0px 10px 0px");
            else
                preloadImages[x].setAttribute("style", "margin-bottom: 10px");
            preloadImages[x].setAttribute("onclick", "changesrc(this.src)");
            preloadImages[x].src = imageLinks[x];
            document.getElementById("image-slide").appendChild(preloadImages[x]);
        }

        if(preloadImages.length != 0) {
            document.getElementById("displayImage").src = preloadImages[0].src;
        }

    //     // alert(imageLinks.length);

    </script>
</body>
</html>