<?php
/**
 * Helper function to get car image data/URL.
 * Handles both Blob data and URL/File paths.
 * 
 * @param Car $car The car object.
 * @param string $prefix Path prefix to reach root (e.g. '../' or '../../').
 * @return string|null The image source for <img> tag or null if no image.
 */
function get_car_image($car, $prefix = '') {
    if ($car->getImageBlob()) {
        $ext = 'jpeg';
        if ($car->getImageUrl()) {
            $ext = pathinfo($car->getImageUrl(), PATHINFO_EXTENSION);
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $ext = 'jpeg';
            }
        }
        return 'data:image/' . $ext . ';base64,' . base64_encode($car->getImageBlob());
    } elseif ($car->getImageUrl()) {
        $url = $car->getImageUrl();
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            // Check if it's in uploads or root
            if (file_exists($prefix . 'uploads/' . $url)) {
                return $prefix . 'uploads/' . $url;
            } else {
                return $prefix . $url;
            }
        }
        return $url;
    }
    return null;
}
