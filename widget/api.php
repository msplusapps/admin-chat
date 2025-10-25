<?php
session_start();
require_once __DIR__ . '/../database.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? null;

if ($action === 'start_conversation') {
    if (!isset($_SESSION['conversation_id'])) {
        $userIp = $_SERVER['REMOTE_ADDR'];
        $stmt = $db->prepare("INSERT INTO conversations (user_ip) VALUES (:user_ip)");
        $stmt->bindValue(':user_ip', $userIp, PDO::PARAM_STR);
        $stmt->execute();
        $_SESSION['conversation_id'] = $db->lastInsertId();
    }
    echo json_encode(['conversation_id' => $_SESSION['conversation_id']]);
    exit;
}

if ($action === 'get_messages') {
    if (!isset($_SESSION['conversation_id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'No active conversation.']);
        exit;
    }
    $conversationId = $_SESSION['conversation_id'];
    $stmt = $db->prepare("SELECT * FROM messages WHERE conversation_id = :conversation_id ORDER BY timestamp ASC");
    $stmt->bindValue(':conversation_id', $conversationId, PDO::PARAM_INT);
    $stmt->execute();
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

if ($action === 'send_message') {
    if (!isset($_SESSION['conversation_id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'No active conversation.']);
        exit;
    }
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['message'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Message is required.']);
        exit;
    }

    $conversationId = $_SESSION['conversation_id'];
    $message = $data['message'];

    $stmt = $db->prepare("INSERT INTO messages (conversation_id, message, sender) VALUES (:conversation_id, :message, 'user')");
    $stmt->bindValue(':conversation_id', $conversationId, PDO::PARAM_INT);
    $stmt->bindValue(':message', htmlspecialchars($message), PDO::PARAM_STR);
    $stmt->execute();

    echo json_encode(['success' => true]);
    exit;
}

http_response_code(400);
echo json_encode(['error' => 'Invalid action.']);
