(function($){

    var copyTextareaBtn = document.querySelector('.copy-linkage');
    var progressWidth   = 0;
    var data_length     = 0;

    $(document).ready(function() {

        copyTextareaBtn.addEventListener('click', function(event) {
            var copyTextarea = document.querySelector('.linkage');
            copyTextarea.focus();
            copyTextarea.select();

            try {
                var successful = document.execCommand('copy');
                var msg = successful ? 'successful' : 'unsuccessful';
                $('.copy_message').fadeIn();

                setTimeout(function(){
                    $('.copy_message').hide();
                }, 2000);

            } catch (err) {
                console.log('Oops, unable to copy');
            }
        });

        $('#file').on("change", function(){
            var file        = $('#file');
            var fn          = $(this).val();
            var filename    = fn.match(/[^\\/]*$/)[0]; // remove C:\fakename
            data_length     = file[0].files[0].size;
            $('.private_upload span').html(filename);
            $('.btn-submit').removeAttr('disabled');
        });

        
        $("#private-media-upload").submit(function(e){

            e.preventDefault();
            $('.progressbar').show();
            $('.private-media').hide();
            var formdata = new FormData();
            var file = $(this).find('input[type="file"]');
            var individual_file = file[0].files[0];
            formdata.append('action', 'upload_private_media');
            formdata.append('file', individual_file);
            formdata.append('private_upload_media_nonce', $('#private_upload_media_nonce').val());
            $('.btn-submit').prop("disabled",true);

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: formdata,
                cache: false,
                dataType: 'json',
                processData: false,
                contentType: false,
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function(evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;
                            percentComplete = parseInt(percentComplete * 100);
                            progressBar(percentComplete)
                        }
                    }, false);
                    return xhr;
                },
                success: function(data, textStatus, jqXHR) {
                    if(data.error == false && data.url != ''){
                        $('.progressbar').fadeOut(1000, function(){
                            $('.uploaded_link input').val(data.url);
                            $('.uploaded_link').fadeIn(500);
                        });
                    }else{
                        alert(data.message);
                        location.reload();
                    }
                }
            });

        });

    });

    function progressBar(width) {
        $('.progressbar-bar').animate({
            width : width + '%'
        },1000, function() {
            $('.progressbar-bar-percent').html(width+ '%');
            if(width === 100){
                $('.progress-msg span').html('Uploaded Successfully.....');
            }
        });
    }

})(jQuery); 