jQuery(document).ready(function($) {
    // Check if origamiParts has the correct URLs
    console.log('Origami Parts:', origamiParts);

    // Initial image state
    $('#origami-step-image').css('background-image', 'url(' + origamiParts.part1 + ')');

    var imageUpdateTimeout; // Variable to hold the timeout reference

    function updateOrigamiImage(fieldIndex) {
        var partImages = [
            origamiParts.part1, // Image for name
            origamiParts.part2, // Image for email
            origamiParts.part3, // Image for subject
            origamiParts.part4  // Image for message
        ];

        $('#origami-step-image').css('background-image', 'none');

        if (fieldIndex >= 0 && fieldIndex < partImages.length) {
            $('#origami-step-image').css('background-image', 'url(' + partImages[fieldIndex] + ')');
        }
    }

    function handleInputChange(fieldIndex) {
        // Clear any existing timeout
        clearTimeout(imageUpdateTimeout);

        // Set a new timeout to delay the image update
        imageUpdateTimeout = setTimeout(function() {
            updateOrigamiImage(fieldIndex);
        }, 300); // Delay of 300 milliseconds (adjust as needed)
    }

    $('input[name="your-name"]').on('input', function() {
        handleInputChange(0);
    });

    $('input[name="your-email"]').on('input', function() {
        handleInputChange(1);
    });

    $('input[name="your-subject"]').on('input', function() {
        handleInputChange(2);
    });

    $('textarea[name="your-message"]').on('input', function() {
        handleInputChange(3);
    });
});
