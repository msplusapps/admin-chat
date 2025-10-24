# PHP Admin Chat Web App

This is a simple admin chat web application built with PHP and SQLite. It allows you to embed a chat widget on your website and manage conversations from an admin panel.

## Features

- Embeddable chat widget
- Admin panel for managing chats
- Real-time messaging using AJAX
- SQLite database for storage
- Secure admin authentication

## Getting Started

Follow these instructions to get the application up and running.

### Prerequisites

- [PHP](https://www.php.net/downloads.php) installed on your system.
- The `php-sqlite3` extension. You can usually install it with `sudo apt-get install php-sqlite3` on Debian-based systems.

### 1. Set Up the Database

First, you need to create the SQLite database and the necessary tables. Run the following command in your terminal from the project's root directory:

```bash
php database.php
```

This will create a `chat.db` file in the root of the project.

### 2. Create an Admin User

Next, you need to create an admin user to access the admin panel. Run the following script:

```bash
php add_admin.php
```

This will create a default admin user with the following credentials:
- **Username:** `admin`
- **Password:** `password`

**Important:** You should change the default password in a production environment for security reasons. You can do this by modifying the `add_admin.php` script before running it.

### 3. Run the Application

To run the application, start the PHP built-in web server:

```bash
php -S localhost:8000
```

This will start a server on port 8000. You can now access the application in your browser:

- **Admin Panel:** [http://localhost:8000/admin](http://localhost:8000/admin)
- **Chat Widget Demo:** [http://localhost:8000/widget](http://localhost:8000/widget)

### 4. How to Embed the Widget

To embed the chat widget on your own website, you'll need to include the necessary CSS and JavaScript files and then initialize the widget.

1.  **Copy the `widget` directory** to your website's project.
2.  **Include the following code** in your HTML file where you want the widget to appear. Make sure to adjust the paths to `widget.css` and `widget.js` if necessary.

```html
<!DOCTYPE html>
<html>
<head>
    <title>Your Website</title>
    <!-- Link to the widget's stylesheet -->
    <link rel="stylesheet" type="text/css" href="path/to/widget/widget.css">
</head>
<body>
    <h1>Welcome to Your Website</h1>
    <p>This is where your content goes.</p>

    <!-- This is where the chat widget will be rendered -->
    <div id="chat-widget-container"></div>

    <!-- Include jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Include the widget's JavaScript file -->
    <script src="path/to/widget/widget.js"></script>

    <!-- Initialize the widget -->
    <script>
        ChatWidget.init({
            container: '#chat-widget-container',
            apiUrl: 'path/to/widget/api.php' // URL to your API endpoint
        });
    </script>
</body>
</html>
```
