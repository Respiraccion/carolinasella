/**
 * Carolina Sella - Contact Form JavaScript
 * 
 * Features:
 * - reCAPTCHA v3 integration
 * - File upload validation & preview
 * - AJAX form submission
 * - Loading states & animations
 */

(function ($) {
    'use strict';

    // Wait for DOM and reCAPTCHA to be ready
    $(document).ready(function () {
        initContactForm();
    });

    function initContactForm() {
        const $form = $('#carolina-contact-form');
        if (!$form.length) return;

        const $submitBtn = $form.find('.submit-btn');
        const $btnText = $submitBtn.find('.btn-text');
        const $btnLoading = $submitBtn.find('.btn-loading');
        const $messages = $form.find('.form-messages');
        const $fileInput = $form.find('#contact-image');
        const $fileName = $form.find('.file-name');
        const $fileClear = $form.find('.file-clear-btn');
        const $imagePreview = $form.find('.image-preview');
        const $previewImg = $imagePreview.find('img');

        // Config from PHP
        const config = window.carolinaContactForm || {};
        const maxFileSize = config.maxFileSize || (5 * 1024 * 1024);
        const maxFileSizeMB = config.maxFileSizeMB || 5;
        const messages = config.messages || {};

        // Allowed image types
        const allowedTypes = [
            'image/jpeg', 'image/jpg', 'image/png', 'image/gif',
            'image/webp', 'image/heic', 'image/heif'
        ];

        // File input change handler
        $fileInput.on('change', function () {
            const file = this.files[0];

            if (!file) {
                resetFileInput();
                return;
            }

            // Validate file type
            if (!isValidImageType(file)) {
                showMessage('error', messages.invalidFileType || 'Invalid file type.');
                resetFileInput();
                return;
            }

            // Validate file size
            if (file.size > maxFileSize) {
                showMessage('error', messages.fileTooLarge || `File too large. Max: ${maxFileSizeMB}MB`);
                resetFileInput();
                return;
            }

            // Show file name
            $fileName.text(truncateFileName(file.name, 30));
            $fileClear.show();

            // Show preview
            showImagePreview(file);
        });

        // Clear file button
        $fileClear.on('click', function (e) {
            e.preventDefault();
            resetFileInput();
        });

        function resetFileInput() {
            $fileInput.val('');
            $fileName.text('No file selected');
            $fileClear.hide();
            $imagePreview.hide();
            $previewImg.attr('src', '');
        }

        function isValidImageType(file) {
            // Check MIME type
            if (allowedTypes.includes(file.type.toLowerCase())) {
                return true;
            }
            // Fallback: check extension
            const ext = file.name.split('.').pop().toLowerCase();
            const validExts = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'heic', 'heif'];
            return validExts.includes(ext);
        }

        function showImagePreview(file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                $previewImg.attr('src', e.target.result);
                $imagePreview.slideDown(200);
            };
            reader.readAsDataURL(file);
        }

        function truncateFileName(name, maxLength) {
            if (name.length <= maxLength) return name;
            const ext = name.split('.').pop();
            const baseName = name.substring(0, name.length - ext.length - 1);
            const truncatedBase = baseName.substring(0, maxLength - ext.length - 4);
            return truncatedBase + '...' + ext;
        }

        // Form submission
        $form.on('submit', function (e) {
            e.preventDefault();

            // Validate required fields
            if (!validateForm()) {
                return;
            }

            // Disable button and show loading
            setLoading(true);
            hideMessage();

            // Get reCAPTCHA token if available
            getRecaptchaToken()
                .then(function (token) {
                    // Set token in hidden field
                    $('#recaptcha-token').val(token);

                    // Submit form
                    submitForm();
                })
                .catch(function (error) {
                    console.error('reCAPTCHA error:', error);
                    // Try to submit anyway (server will validate)
                    submitForm();
                });
        });

        function validateForm() {
            let valid = true;
            const requiredFields = ['#contact-name', '#contact-email', '#contact-message'];

            requiredFields.forEach(function (selector) {
                const $field = $(selector);
                const value = $field.val().trim();

                if (!value) {
                    $field.addClass('error');
                    valid = false;
                } else {
                    $field.removeClass('error');
                }
            });

            // Validate email format
            const $email = $('#contact-email');
            const emailValue = $email.val().trim();
            if (emailValue && !isValidEmail(emailValue)) {
                $email.addClass('error');
                valid = false;
                showMessage('error', 'Please enter a valid email address.');
                return false;
            }

            if (!valid) {
                showMessage('error', 'Please complete all required fields.');
            }

            return valid;
        }

        function isValidEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }

        function getRecaptchaToken() {
            return new Promise(function (resolve, reject) {
                const siteKey = config.recaptchaSiteKey;

                if (!siteKey || typeof grecaptcha === 'undefined') {
                    resolve(''); // No reCAPTCHA configured
                    return;
                }

                grecaptcha.ready(function () {
                    grecaptcha.execute(siteKey, { action: 'contact_form' })
                        .then(resolve)
                        .catch(reject);
                });
            });
        }

        function submitForm() {
            const formData = new FormData($form[0]);
            formData.append('action', 'carolina_contact_form_submit');
            formData.append('nonce', config.nonce);

            $.ajax({
                url: config.ajaxUrl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    setLoading(false);

                    if (response.success) {
                        showMessage('success', response.data.message);
                        $form[0].reset();
                        resetFileInput();
                    } else {
                        showMessage('error', response.data.message || messages.error);
                    }
                },
                error: function (xhr, status, error) {
                    setLoading(false);
                    console.error('Form submission error:', error);
                    showMessage('error', messages.error || 'Error sending message.');
                }
            });
        }

        function setLoading(loading) {
            if (loading) {
                $submitBtn.prop('disabled', true).addClass('loading');
                $btnText.hide();
                $btnLoading.show();
            } else {
                $submitBtn.prop('disabled', false).removeClass('loading');
                $btnText.show();
                $btnLoading.hide();
            }
        }

        function showMessage(type, message) {
            $messages
                .removeClass('success error')
                .addClass(type)
                .html(message)
                .slideDown(200);

            // Scroll to message
            $('html, body').animate({
                scrollTop: $messages.offset().top - 100
            }, 300);
        }

        function hideMessage() {
            $messages.slideUp(200);
        }

        // Remove error class on input
        $form.find('input, textarea, select').on('input change', function () {
            $(this).removeClass('error');
        });
    }

})(jQuery);
