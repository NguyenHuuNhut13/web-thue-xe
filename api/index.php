<?php

// Force error reporting on Vercel for diagnostic purposes
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set working directory to project root
chdir(__DIR__ . '/../');

// Forward Vercel requests to normal index.php
$_SERVER['SCRIPT_NAME'] = '/index.php';

require __DIR__ . '/../public/index.php';
