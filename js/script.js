<script type="text/javascript" src="js/jquery-1.12.4.min.js"></script>
<script type="text/javascript">

    const camera = document.getElementById('camera');
    const canvas = document.createElement('canvas');
    const image = document.getElementById('image');
    const constraints = { video : true };

    // Forces to use the rear video camera in mobile
    //const constraints = { video : { facingMode : { exact : 'environment' } } };

    $(document).ready(function() {

        // Uploads file
        $('#image-file').change(function() {
            closeCamera();
            var file = this.files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
                image.src = e.target.result;
                extractText();
            }
            reader.readAsDataURL(file);
        });

        // Turns on the video camera
        $('#camera-switch').click(function() {
            openCamera();
        });

        // Capture image from the video camera
        $('#capture').click(function() {
            closeCamera();
            canvas.getContext('2d').drawImage(camera, 0, 0, 300, 150);
            image.src = canvas.toDataURL('image/jpg');
            extractText();
        });
    });

    // Opens the video camera for capture
    function openCamera() {
        $('#message').hide();
        $('#camera-switch').hide().val(0);
        $('#capture').show();
        $('#image-container').hide();
        $('#camera-container').show();
        $('#image-file').val('');
        navigator.mediaDevices.getUserMedia(constraints)
            .then(function (stream) {
                var video = $('#camera')[0];
                video.srcObject = stream;
                video.onloadedmetadata = function(e) {
                    video.play();
                }
            })
            .catch(function (error) {
                $('#message').html('<span class="alert alert-danger full-width"><strong>Error.</strong> ' + error + '</span>').fadeIn();
            });
    }

    // Closes the video camera
    function closeCamera() {
        $('#capture').hide();
        $('#camera-switch').show().val(1);
        $('#camera-container').hide();
        $('#image-container').show();
        $(image).show();
        if ($('#camera')[0].srcObject) {
            $.each($('#camera')[0].srcObject.getTracks(), function (index, track) {
                track.stop();
            });
        }
    }

    // Extracts the text from the image displayed
    function extractText() {
        $('.progress-display').fadeIn();
        var message = '';
        $('#message').hide();
        $.ajax({
            url: '/gibocode-atm-card-ocr/process',
            type: 'post',
            data: {
                image: image.src
            },
            dataType: 'json',
            success: function(data) {

                $('.progress-display').fadeOut();

                if (data.text != '') {
                    if (data.card_number != '') {
                        $('#card-number').text('Card No.: ' + data.card_number);
                        return;
                    }

                    message = 'No card number extracted.';
                }

                if (message == '') {
                    message = 'Could not extract text from the image.';
                }

                console.log(data.text);

                $('#card-number').text('Card No.: #### #### #### ####');
                $('#message').html('<span class="alert alert-danger full-width"><strong>Error.</strong> ' + message + '</span>').fadeIn();
            },
            error: function(xhr, data, message) {
                $('.progress-display').fadeOut();
                var errorMessage = xhr.responseText;
                if (errorMessage == '') {
                    errorMessage = message;
                }
                $('#message').html('<span class="alert alert-danger full-width"><strong>Error.</strong> ' + errorMessage + '</span>').fadeIn();
            }
        });
    }

</script>
