<?php
require 'vendor/autoload.php';

use SimpleSoftwareIO\QrCode\Facades\QrCode;

try {
    echo "Starting QR Code test...\n";
    $qr = QrCode::format('svg')->size(300)->generate('Test SKU');
    echo "QR Code generated successfully. Length: " . strlen($qr) . "\n";
} catch (\Throwable $e) {
    echo "ERROR caught: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
