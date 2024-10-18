// bihani-social-share.js
jQuery(document).ready(function($) {
    // Track click events on share buttons
    $('.bihani-share-button').on('click', function(e) {
        // Optional: Add your tracking code here, for example, Google Analytics
        console.log('Share button clicked:', $(this).text());

        // You can also add any additional functionality here if needed
    });

    // Optional: Enhance the share buttons with some animations or effects
    $('.bihani-share-button').hover(
        function() {
            $(this).css('transform', 'scale(1.05)');
        },
        function() {
            $(this).css('transform', 'scale(1)');
        }
    );
});
