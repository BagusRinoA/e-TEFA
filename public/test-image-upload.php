<?php

/**
 * Image Upload Diagnostic Test
 *
 * Akses via: http://localhost:8000/test-image-upload.php
 */

echo "<h1>🧪 Image Upload Diagnostic Test</h1>";
echo "<hr>";

// 1. Check GD Extension
echo "<h2>1. GD Extension Status</h2>";
if (extension_loaded('gd')) {
    echo "✅ GD Extension: <strong>ENABLED</strong><br>";
    $gdInfo = gd_info();
    echo "GD Version: " . ($gdInfo['GD Version'] ?? 'N/A') . "<br>";
} else {
    echo "❌ GD Extension: <strong>DISABLED</strong><br>";
    echo "Please enable GD in php.ini and restart the server<br>";
}
echo "<hr>";

// 2. Check GD Functions
echo "<h2>2. GD Functions Availability</h2>";
$functions = [
    'imagecreatefromjpeg',
    'imagecreatefrompng',
    'imagecreatefromgif',
    'imagecreatefromstring',
    'imagecolorallocate',
    'imagestring',
    'imagejpeg',
    'imagepng',
];

foreach ($functions as $func) {
    $status = function_exists($func) ? '✅' : '❌';
    echo "$status $func<br>";
}
echo "<hr>";

// 3. Test Intervention/Image
echo "<h2>3. Intervention/Image Test</h2>";
try {
    require_once __DIR__ . '/vendor/autoload.php';

    $manager = new \Intervention\Image\ImageManager(
        new \Intervention\Image\Drivers\Gd\Driver()
    );

    echo "✅ ImageManager initialized successfully<br>";

    // Test reading a simple image
    $testImage = __DIR__ . '/public/test-image.png';
    if (file_exists($testImage)) {
        $image = $manager->read($testImage);
        echo "✅ Image read successfully<br>";
        echo "   Width: " . $image->width() . "px<br>";
        echo "   Height: " . $image->height() . "px<br>";
    } else {
        echo "⚠️  Test image not found (create a test PNG first)<br>";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
    echo "   File: " . $e->getFile() . "<br>";
    echo "   Line: " . $e->getLine() . "<br>";
}
echo "<hr>";

// 4. Check Service
echo "<h2>4. ImageUploadService Test</h2>";
try {
    require_once __DIR__ . '/bootstrap/app.php';

    $service = app(\App\Services\ImageUploadService::class);
    echo "✅ ImageUploadService loaded<br>";

    $methods = get_class_methods($service);
    echo "Available methods:<br>";
    foreach ($methods as $method) {
        if (strpos($method, '_') !== 0) {
            echo "   • $method()<br>";
        }
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}
echo "<hr>";

// 5. Check Storage Directory
echo "<h2>5. Storage Directory Structure</h2>";
$storagePath = __DIR__ . '/storage/app/public';
if (is_dir($storagePath)) {
    echo "✅ Storage path exists: $storagePath<br>";

    $folders = ['profiles', 'articles', 'products', 'forum', 'redemption-items'];
    foreach ($folders as $folder) {
        $folderPath = $storagePath . '/' . $folder;
        if (is_dir($folderPath)) {
            echo "✅ $folder/<br>";
        } else {
            echo "❌ $folder/ (missing)<br>";
        }
    }
} else {
    echo "❌ Storage path does not exist<br>";
}
echo "<hr>";

// 6. Check Symlink
echo "<h2>6. Storage Symlink</h2>";
$symlink = __DIR__ . '/public/storage';
if (is_link($symlink)) {
    echo "✅ Symlink exists<br>";
    echo "   Target: " . readlink($symlink) . "<br>";
} else {
    echo "❌ Symlink does not exist<br>";
    echo "   Run: php artisan storage:link<br>";
}
echo "<hr>";

echo "<h2>Summary</h2>";
echo "If all checks pass ✅, the image upload should work!<br>";
echo "If any checks fail ❌, fix them according to the instructions above.";
