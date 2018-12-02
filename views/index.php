<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link type="text/css" rel="stylesheet" href="css/bootstrap.min.css"/>
        <link type="text/css" rel="stylesheet" href="css/font-awesome/all.css"/>
        <link type="text/css" rel="stylesheet" href="css/style.css"/>
    </head>
    <body>
        <div id="container">
            <div class="col-lg-4 center content">
                <div class="full-width" id="media-box">
                    <button class="btn btn-success" id="capture">
                        <span class="fa fa-camera"></span>  Capture
                    </button>
                    <button class="btn btn-primary" id="camera-switch" value="0">
                        <span class="fa fa-video"></span> Camera On
                    </button>
                    <div id="camera-container">
                        <video id="camera"></video>
                    </div>
                    <div id="image-container">
                        <img id="image">
                    </div>
                    <div class="progress-display">
                        <span>Extracting text...</span>
                        <div id="progress-bar-display">
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="message"></div>
                <input class="full-width" id="image-file" type="file" accepts="image/*">
                <div id="card-info">
                    <div id="card-number">Card No.: #### #### #### ####</div>
                    <div id="expr">Expiration: 00/00</div>
                </div>
            </div>
        </div>
    </body>

    <?php include "js/script.js"; ?>

</html>
