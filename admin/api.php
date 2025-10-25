<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    exit;
}
require_once __DIR__ . '/../database.php';

header('Content-Type: application/json');

$action = $_GET['action'];

if ($action === 'get_conversations') {
    $stmt = $db->query("SELECT * FROM conversations ORDER BY created_at DESC");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

if ($action === 'get_messages') {
    if (!isset($_GET['conversation_id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Conversation ID is required.']);
        exit;
    }
    $conversationId = $_GET['conversation_id'];
    $stmt = $db->prepare("SELECT * FROM messages WHERE conversation_id = :conversation_id ORDER BY timestamp ASC");
    $stmt->bindValue(':conversation_id', $conversationId, PDO::PARAM_INT);
    $stmt->execute();
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

if ($action === 'send_message') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['conversation_id'], $data['message'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Conversation ID and message are required.']);
        exit;
    }
    $conversationId = $data['conversation_id'];
    $message = $data['message'];

    $stmt = $db->prepare("INSERT INTO messages (conversation_id, message, sender) VALUES (:conversation_id, :message, 'admin')");
    $stmt->bindValue(':conversation_id', $conversationId, PDO::PARAM_INT);
    $stmt->bindValue(':message', htmlspecialchars($message), PDO::PARAM_STR);
    $stmt->execute();

    echo json_encode(['success' => true]);
}
