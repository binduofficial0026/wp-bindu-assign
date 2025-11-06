jQuery(document).ready(function($){
    var mediaUploader;

    // Upload Image
    $('#upload_image_button').click(function(e){
        e.preventDefault();

        if(mediaUploader){ 
            mediaUploader.open(); 
            return; 
        }

        mediaUploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: { text: 'Choose Image' },
            multiple: false
        });

        mediaUploader.on('select', function(){
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            $('#image_url').val(attachment.url);
            $('#image_preview').attr('src', attachment.url).show();
            $('#remove_image_button').show(); // Show remove button
        });

        mediaUploader.open();
    });

    // Remove Image
    $('#remove_image_button').click(function(){
        $('#image_url').val('');
        $('#image_preview').hide().attr('src','');
        $(this).hide(); // Hide remove button
    });
});
