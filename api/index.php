<?php

// Force error reporting on Vercel for diagnostic purposes
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Diagnostic check to see if composer vendor folder exists
if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
    header('HTTP/1.1 500 Internal Server Error');
    echo "<h1>Laravel Bootstrap Error</h1>";
    echo "<p><strong>Error:</strong> <code>vendor/autoload.php</code> was not found on the server.</p>";
    echo "<p>This indicates that Vercel did not run <code>composer install</code> during the deployment phase, or composer failed.</p>";
    echo "<p>Current working directory: " . getcwd() . "</p>";
    echo "<p>Directory contents of root:</p><pre>";
    print_r(scandir(__DIR__ . '/../'));
    echo "</pre>";
    exit;
}

// Set working directory to project root
chdir(__DIR__ . '/../');

// Forward Vercel requests to normal index.php
$_SERVER['SCRIPT_NAME'] = '/index.php';

require __DIR__ . '/../public/index.php';
