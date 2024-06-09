<?php
$uploadDir = 'uploads/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Delete files that are older than 1 day
foreach (glob($uploadDir . '*') as $file) {
    if (filemtime($file) < time() - 24 * 60 * 60) {
        unlink($file);
    }
}