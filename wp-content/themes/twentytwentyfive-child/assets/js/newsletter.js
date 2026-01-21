jQuery(document).ready(function ($) {
    $('.newsletter-form').on('submit', function (e) {
        e.preventDefault();

        var $form = $(this);
        var $input = $form.find('input[type="email"]');
        var $button = $form.find('button');
        var originalBtnText = $button.text();

        // Disable form
        $input.prop('disabled', true);
        $button.prop('disabled', true).text(carolinaNewsletter.loading);

        $.ajax({
            url: carolinaNewsletter.ajaxUrl,
            type: 'POST',
            data: {
                action: 'carolina_newsletter_subscribe',
                email: $input.val(),
                nonce: carolinaNewsletter.nonce
            },
            success: function (response) {
                if (response.success) {
                    // Success state
                    $form.html('<p class="newsletter-success" style="color:var(--wp--preset--color--grafito); text-align:center; font-family:var(--wp--preset--font-family--lato);">' + response.data.message + '</p>');
                } else {
                    // Error state
                    alert(response.data.message);
                    $input.prop('disabled', false);
                    $button.prop('disabled', false).text(originalBtnText);
                }
            },
            error: function () {
                alert('Connection failed. Please try again.');
                $input.prop('disabled', false);
                $button.prop('disabled', false).text(originalBtnText);
            }
        });
    });
});
