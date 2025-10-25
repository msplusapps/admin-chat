<?php
// Session is now started and managed by the router.
// The login handler is now included by the router.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="bg-gray-900 flex items-center justify-center h-screen">
    <div class="bg-gray-800 p-8 rounded-2xl shadow-2xl w-full max-w-sm">
        <h2 class="text-white text-3xl font-bold mb-6 text-center">Admin Access</h2>
        <form method="post" action="/admin/login">
            <?php if (isset($error)): ?>
                <div class="bg-red-500/20 border border-red-500 text-red-300 p-3 rounded-md mb-4 text-center">
                    <p><?php echo htmlspecialchars($error); ?></p>
                </div>
            <?php endif; ?>
            <div class="mb-4 relative">
                <i class="bi bi-person-fill absolute text-gray-400 top-3.5 left-4"></i>
                <input type="text" id="username" name="username" placeholder="Enter your username" required
                       class="bg-gray-700 text-white border-2 border-gray-600 rounded-lg w-full py-3 px-4 pl-12 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300">
            </div>
            <div class="mb-6 relative">
                <i class="bi bi-lock-fill absolute text-gray-400 top-3.5 left-4"></i>
                <input type="password" id="password" name="password" placeholder="Enter your password" required
                       class="bg-gray-700 text-white border-2 border-gray-600 rounded-lg w-full py-3 px-4 pl-12 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300">
            </div>
            <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300 ease-in-out transform hover:scale-105 flex items-center justify-center">
                <i data-feather="log-in" class="mr-2"></i>
                Login
            </button>
        </form>
    </div>
    <script>
      feather.replace()
    </script>
</body>
</html>
