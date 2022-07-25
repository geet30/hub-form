$(document).ready(function () {
    $('.dynamicAppendChat').empty();
    var messageBody = document.querySelector('#mainChatSection');
    messageBody.scrollTop = messageBody.scrollHeight - messageBody.clientHeight;


    $('.clipImage').click(function () {
        $('#preview_image').css('display', 'none');
        $('.removePreviewDynamic').css('display', 'none');
        $('#file_name').val('');
        $('#file').click();
    });

    $('#file').change(function () {
        if ($(this).val() != '') {
            upload(this);
        }
    });


    var firebaseConfig = {
        apiKey: "AIzaSyDZCgvUdwc71BjVRIHO7dqoPPe_FO5yZ5w",
        authDomain: "p2b-app.firebaseapp.com",
        databaseURL: "https://p2b-app.firebaseio.com",
        projectId: "p2b-app",
        storageBucket: "p2b-app.appspot.com",
        messagingSenderId: "319358867912",
        appId: "1:319358867912:web:ae9befc8473cfaf52c08fd"
    };

    firebase.initializeApp(firebaseConfig);

    function upload(img) {
        var siteUrl = $('#site_url').val();
        $('#loading').css('display', 'block');
        var image = img.files[0];
        var imageName = image.name;
        var extension = imageName.substring(imageName.lastIndexOf('.') + 1);
        var storageRef = firebase.storage().ref('chat_media/' + imageName);
        var uploadTask = storageRef.put(image);
        uploadTask.on('state_changed', function (snapshot) {
            var progress = (snapshot.bytesTransferred / snapshot.totalBytes) * 100;
            console.log("upload is " + progress + " done");
        }, function (error) {
            console.log(error.message);
            $('#loading').css('display', 'none');
        }, function () {
            uploadTask.snapshot.ref.getDownloadURL().then(function (downlaodURL) {
                console.log(downlaodURL);
                $('#preview_image').css('display', 'block');
                $('.removePreviewDynamic').css('display', 'block');
                $('#file_name').val(downlaodURL);
                $('.mediaExt').val(extension);
                if (extension == 'jpg' || extension == 'jpeg' || extension == 'png' || extension == 'gif')
                    $('#preview_image').attr('src', downlaodURL);
                else if (extension == 'pdf')
                    $('#preview_image').attr('src', siteUrl + '/pdf.png');
                else if (extension == 'mp4')
                    $('#preview_image').attr('src', siteUrl + '/mp4.png');
                else if (extension == 'mp3')
                    $('#preview_image').attr('src', siteUrl + '/mp3.png');
                else if (extension == 'doc' || extension == 'docx' || extension == 'docm' || extension == 'csv')
                    $('#preview_image').attr('src', siteUrl + '/doc.png');
                else
                    $('#preview_image').attr('src', siteUrl + '/file.png');
                $('#loading').css('display', 'none');
            });
        });
    }

    function removeMediaPreview() {
        var downloadUrl = $('#file_name').val();
        if (downloadUrl != "") {
            var storageRef = firebase.storage().refFromURL(downloadUrl);
            storageRef.delete().then(function () {
                console.log('deleted');
                $('#file_name').val('');
                $('#file').val('');
                $('.mediaExt').val('');
                $('#preview_image').css('display', 'none');
                $('#preview_image').attr('src', '');
                $('.removePreviewDynamic').css('display', 'none');
                // File deleted successfully
            }).catch(function (error) {
                console.log('not deleted');
            });
        }
    }

    $("#enableEditChat").click(function () {
        var currentTimeStamp = new Date().getTime();
        $("#current_time").val(currentTimeStamp);
        
        var chatText = $('textarea#messageTextarea').val();
        var filePath = $('#file_name').val();

        if (chatText != "" || filePath != "") {
            $('#loading').css('display', 'block');
            $.ajax({
                url: '/admin/chatting',
                type: 'POST',
                data: $("#chatForm").serialize(),
                dataType: 'JSON',
                success: function (data) {
                    if (data.status == true) {
                        var message = data.message;
                        $('textarea#messageTextarea').val('');
                        $('.loadMediaChat').empty();
                        $('.dynamicAppendChat').html(data.chatHtml);
                        var d = $("#mainChatSection");
                        d.scrollTop(d[0].scrollHeight);
                        $('#preview_image').css('display', 'none');
                        $('.removePreviewDynamic').css('display', 'none');
                        $('#file_name').val('');
                    } else {
                        var message = data.message;
                        alert(message);
                        $('#loading').css('display', 'none');
                    }
                    $('#loading').css('display', 'none');
                }
            });
        } else {
            alert("Please Type Message.");
        }
    });
});
