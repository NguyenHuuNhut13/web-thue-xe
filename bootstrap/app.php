<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->report(function (\Throwable $e) {
            header('HTTP/1.1 500 Internal Server Error');
            header('Content-Type: text/html; charset=utf-8');
            echo '<h1>Intercepted Original Exception</h1>';
            echo '<p><strong>Message:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
            echo '<p><strong>Class:</strong> ' . get_class($e) . '</p>';
            echo '<p><strong>File:</strong> ' . htmlspecialchars($e->getFile()) . ':' . $e->getLine() . '</p>';
            
            // Debug connection info
            echo '<h2>Resolved DB Config:</h2>';
            echo '<pre>';
            $config = config('database.connections.pgsql');
            // Hide password for safety but show length and hint for debugging
            if (isset($config['password'])) {
                $len = strlen($config['password']);
                if ($len > 6) {
                    $config['password'] = substr($config['password'], 0, 3) . '...' . substr($config['password'], -3) . ' (length: ' . $len . ')';
                } else {
                    $config['password'] = str_repeat('*', $len);
                }
            }
            print_r($config);
            echo '</pre>';
            
            echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
            exit;
        });
    })
    ->booting(function () {
        // Resolve nested environment variables if any (e.g. DB_HOST=${POSTGRES_HOST})
        foreach ($_ENV as $key => $value) {
            if (is_string($value) && strpos($value, '$') !== false) {
                $resolved = preg_replace_callback('/\${([^}]+)}/', function ($m) {
                    return $_ENV[$m[1]] ?? $_SERVER[$m[1]] ?? '';
                }, $value);
                $_ENV[$key] = $resolved;
                putenv("$key=$resolved");
            }
        }
        foreach ($_SERVER as $key => $value) {
            if (is_string($value) && strpos($value, '$') !== false) {
                $resolved = preg_replace_callback('/\${([^}]+)}/', function ($m) {
                    return $_ENV[$m[1]] ?? $_SERVER[$m[1]] ?? '';
                }, $value);
                $_SERVER[$key] = $resolved;
            }
        }

        // Recursively resolve any ${VAR} in the database configuration
        $resolveValue = function ($val) use (&$resolveValue) {
            if (is_array($val)) {
                foreach ($val as $k => $v) {
                    $val[$k] = $resolveValue($v);
                }
                return $val;
            }
            if (is_string($val)) {
                if (strpos($val, '$') !== false) {
                    $val = preg_replace_callback('/\${([^}]+)}/', function ($m) {
                        return $_ENV[$m[1]] ?? $_SERVER[$m[1]] ?? '';
                    }, $val);
                }
                // Automatically convert direct Supabase host (IPv6 only) or incorrect pooler host to correct IPv4 pooler host on Vercel
                if (strpos($val, 'db.nybdguceocdzeqlaubjp.supabase.co') !== false || 
                    strpos($val, 'aws-0-ap-southeast-1.pooler.supabase.com') !== false) {
                    return 'aws-1-ap-southeast-1.pooler.supabase.com';
                }
            }
            return $val;
        };
        
        $dbConfig = $resolveValue(config('database'));
        
        // Ensure username has the tenant identifier if connecting to Supabase pooler
        if (isset($dbConfig['connections']['pgsql']['host']) && 
            strpos($dbConfig['connections']['pgsql']['host'], 'pooler.supabase.com') !== false) {
            
            // Set port to 5432 (Session Mode) which supports Laravel prepared statements
            $dbConfig['connections']['pgsql']['port'] = 5432;
            
            // Force sslmode to require for SNI routing support on Vercel
            $dbConfig['connections']['pgsql']['sslmode'] = 'require';
            
            $username = $dbConfig['connections']['pgsql']['username'] ?? '';
            if ($username && strpos($username, '.') === false) {
                $dbConfig['connections']['pgsql']['username'] = $username . '.nybdguceocdzeqlaubjp';
            }
        }
        
        config(['database' => $dbConfig]);

        if (config('app.env') === 'production' || env('VERCEL')) {
            // Set view compiled path to /tmp/views on Vercel
            $viewCompiledPath = '/tmp/views';
            if (!is_dir($viewCompiledPath)) {
                mkdir($viewCompiledPath, 0755, true);
            }
            config(['view.compiled' => $viewCompiledPath]);
            
            // Set session files path to /tmp/sessions if session driver is file
            $sessionPath = '/tmp/sessions';
            if (!is_dir($sessionPath)) {
                mkdir($sessionPath, 0755, true);
            }
            config(['session.files' => $sessionPath]);
        }
    })
    ->create();
