(function ($) {
    'use strict';

    // Admin JavaScript for zzprompts theme
    $(document).ready(function () {

        // Generic Media Uploader for Widgets
        $(document).on('click', '.zz-upload-image-button', function (e) {
            e.preventDefault();
            var $button = $(this);
            var $input = $button.prev('.zz-image-url'); // Input field (must be immediately before button)

            // If the frame already exists, re-open it.
            if ($button.data('frame')) {
                $button.data('frame').open();
                return;
            }

            // Create the media frame.
            var frame = wp.media({
                title: 'Select Image',
                button: {
                    text: 'Use this image'
                },
                multiple: false // Set to true to allow multiple files to be selected
            });

            // Keep reference to frame
            $button.data('frame', frame);

            // When an image is selected, run a callback.
            frame.on('select', function () {
                var attachment = frame.state().get('selection').first().toJSON();
                $input.val(attachment.url).trigger('change');
            });

            // Finally, open the modal.
            frame.open();
        });

    });

})(jQuery);
