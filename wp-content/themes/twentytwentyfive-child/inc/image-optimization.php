<?php
/**
 * Image Optimization Logic
 * 
 * Handles HEIC conversion and auto-compression/optimization using Imagick.
 * Converts uploaded images (HEIC, JPG, PNG) to WebP for best performance.
 */

class Carolina_Image_Optimizer {
    
    private $quality = 82;
    private $target_format = 'webp';

    public function __construct() {
        // CRITICAL: Must add HEIC to allowed mimes FIRST
        add_filter('upload_mimes', [$this, 'add_allowed_mimes'], 1);
        
        // CRITICAL: Bypass WordPress file type security check for HEIC
        add_filter('wp_check_filetype_and_ext', [$this, 'allow_heic_upload'], 99, 5);
        
        // Process image AFTER WordPress accepts it
        add_filter('wp_handle_upload', [$this, 'process_uploaded_image'], 10, 2);
        
        // Quality filters
        add_filter('jpeg_quality', function() { return $this->quality; });
        add_filter('wp_editor_set_quality', function() { return $this->quality; });
        add_filter('big_image_size_threshold', '__return_false');
    }

    /**
     * Add HEIC/WebP to allowed upload types.
     */
    public function add_allowed_mimes($mimes) {
        $mimes['webp'] = 'image/webp';
        $mimes['heic'] = 'image/heic';
        $mimes['heif'] = 'image/heif';
        return $mimes;
    }

    /**
     * Force WordPress to accept HEIC files by overriding the file check result.
     */
    public function allow_heic_upload($data, $file, $filename, $mimes, $real_mime = false) {
        // Get the file extension
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        // If it's a HEIC/HEIF file and WordPress rejected it, force accept
        if (in_array($ext, ['heic', 'heif'])) {
            return [
                'ext' => $ext,
                'type' => 'image/' . $ext,
                'proper_filename' => false
            ];
        }
        
        return $data;
    }

    /**
     * Process the uploaded image AFTER WordPress has accepted it.
     * Convert to WebP and optimize.
     */
    public function process_uploaded_image($upload, $context = 'upload') {
        // Only process images
        if (strpos($upload['type'], 'image/') !== 0) {
            return $upload;
        }

        // Check if Imagick is available
        if (!extension_loaded('imagick')) {
            error_log('Carolina Image Optimizer: Imagick not available');
            return $upload;
        }

        $file_path = $upload['file'];
        $ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
        
        // Only process these formats
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'heic', 'heif'])) {
            return $upload;
        }

        try {
            $image = new Imagick();
            $image->readImage($file_path);
            
            // Convert to WebP
            $image->setImageFormat($this->target_format);
            $image->setImageCompressionQuality($this->quality);
            $image->setOption('webp:method', '6');
            $image->setOption('webp:lossless', 'false');
            $image->stripImage();

            // Generate new file path
            $dir = dirname($file_path);
            $basename = pathinfo($file_path, PATHINFO_FILENAME);
            $new_path = $dir . '/' . $basename . '.' . $this->target_format;
            
            // Write the WebP
            $image->writeImage($new_path);
            chmod($new_path, 0644);
            
            $image->clear();
            $image->destroy();

            // Delete the original file
            if ($file_path !== $new_path && file_exists($file_path)) {
                unlink($file_path);
            }

            // Update upload data
            $upload['file'] = $new_path;
            $upload['url'] = str_replace($ext, $this->target_format, $upload['url']);
            $upload['type'] = 'image/' . $this->target_format;
            
        } catch (Exception $e) {
            error_log('Carolina Image Optimizer Error: ' . $e->getMessage());
        }

        return $upload;
    }
}

// Initialize
new Carolina_Image_Optimizer();
