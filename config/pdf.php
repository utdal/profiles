<?php

return [

    /**
     * Path to the chromium executable
     */
    'chrome' => env('CHROME_PATH'),

    /**
     * Additional arguments to pass to Chromium
     * Specify as ['key' => 'value'] for --key=value
     */
    'chrome_arguments' => json_decode(env('CHROME_ARGS'), true) ?? [
        'autoplay-policy' => 'user-gesture-required',
        'disable-component-update',
        'disable-domain-reliability',
        'disable-features' => 'AudioServiceOutOfProcess,IsolateOrigins,site-per-process',
        'disable-print-preview',
        'disable-setuid-sandbox',
        'disable-site-isolation-trials',
        'disable-speech-api',
        'disk-cache-size' => 33554432,
        'enable-features' => 'SharedArrayBuffer',
        'font-render-hinting' => 'none',
        'hide-scrollbars',
        'ignore-gpu-blocklist',
        'in-process-gpu',
        'mute-audio',
        'no-default-browser-check',
        'no-pings',
        'no-sandbox',
        'no-zygote',
        'use-gl' => 'swiftshader',
        'window-size' => '1920,1080',
        'single-process',
    ],

    /**
     * Path to the node executable
     */
    'node' => env('NODE_PATH'),

    /**
     * Path to the NPM executable
     */
    'npm' => env('NODE_NPM_PATH'),

    /**
     * Path to the node_modules where Puppeteer is installed
     */
    'node_modules' => env('NODE_MODULES_PATH'),

];