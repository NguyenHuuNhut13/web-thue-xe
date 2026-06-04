<?php

// Forward Vercel requests to normal index.php
$_SERVER['SCRIPT_NAME'] = '/index.php';

require __DIR__ . '/../public/index.php';
