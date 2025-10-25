<?php
session_start();

$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$is_logged_in = isset($_SESSION['admin_id']);

// --- Centralized Authentication & Routing Logic ---

// If a logged-in user tries to access the login page, redirect them.
if ($is_logged_in && $request_uri === '/admin/login') {
    header('Location: /admin');
    exit;
}

// If a logged-out user tries to access a protected page, redirect them.
$protected_routes = ['/admin', '/admin/api'];
if (!$is_logged_in && in_array($request_uri, $protected_routes)) {
    header('Location: /admin/login');
    exit;
}

// Serve static files directly
if (preg_match('/\.(?:js|css|png|jpg|jpeg|gif)$/', $request_uri)) {
    $filepath = __DIR__ . $request_uri;
    if (file_exists($filepath)) {
        // ... (static file serving logic remains the same)
        $content_types = [
            'js' => 'application/javascript', 'css' => 'text/css',
            'png' => 'image/png', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'gif' => 'image/gif',
        ];
        $extension = pathinfo($filepath, PATHINFO_EXTENSION);
        header('Content-Type: ' . ($content_types[$extension] ?? 'application/octet-stream'));
        readfile($filepath);
        exit;
    }
}

// --- Route to the correct file ---

switch ($request_uri) {
    case '/':
        require __DIR__ . '/index.php';
        break;
    case '/admin':
        require __DIR__ . '/admin/index.php';
        break;
    case '/admin/login':
        // **FIX:** Include the handler here, before the view.
        require __DIR__ . '/admin/handlers/login_handler.php';
        require __DIR__ . '/admin/login.php';
        break;
    case '/admin/logout':
        require __DIR__ . '/admin/logout.php';
        break;
    case '/admin/api':
        require __DIR__ . '/admin/api.php';
        break;
    case '/widget':
        require __DIR__ . '/widget/index.html';
        break;
    case '/widget/api':
        require __DIR__ . '/widget/api.php';
        break;
    default:
        http_response_code(404);
        echo "<h1>404 Not Found</h1><p>The page you requested could not be found.</p>";
        break;
}
