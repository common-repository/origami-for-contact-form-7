jQuery(document).ready(function($) {
    $('.upload-image-button').click(function(e) {
        e.preventDefault();
        var button = $(this);
        var target = $(button.data('target'));
        var preview = $('#preview_' + target.attr('id'));

        // Create the media frame
        var frame = wp.media({
            title: 'Select or Upload Image',
            button: {
                text: 'Use this image'
            },
            multiple: false
        });

        // When an image is selected, run a callback
        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            target.val(attachment.id);
            preview.html('<img src="' + attachment.url + '" style="max-width: 100px; height: auto;" />');
        });

        // Open the media frame
        frame.open();
    });
});
