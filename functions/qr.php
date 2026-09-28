<?php
require_once __DIR__ . '/../vendor/autoload.php';

use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use BaconQrCode\Renderer\Module\RoundnessModule;

function generateQRToken() {
    return bin2hex(random_bytes(32));
}

function generateQRImage($text, $scale = 5) {
    $renderer = new ImageRenderer(
        new RendererStyle(300, 4, new RoundnessModule(0.5)),
        new SvgImageBackEnd()
    );
    $writer = new Writer($renderer);
    return $writer->writeString($text);
}

function getQRDataUrl($text) {
    $svg = generateQRImage($text);
    return 'data:image/svg+xml;base64,' . base64_encode($svg);
}