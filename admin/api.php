<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    exit;
}

require_once '../database.php';

header('Content-Type: application/json');

$action = $_GET['action'];

if ($action === 'get_conversations') {
    $stmt = $db->query("SELECT * FROM conversations");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

if ($action === 'get_messages') {
    $conversationId = $_GET['conversation_id'];
    $stmt = $db->prepare("SELECT * FROM messages WHERE conversation_id = :conversation_id");
    $stmt->bindValue(':conversation_id', $conversationId, PDO::PARAM_INT);
    $stmt->execute();
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

if ($action === 'send_message') {
    $conversationId = $_POST['conversation_id'];
    $message = $_POST['message'];
    $sender = $_POST['sender'];

    $stmt = $db->prepare("INSERT INTO messages (conversation_id, message, sender) VALUES (:conversation_id, :message, :sender)");
    $stmt->bindValue(':conversation_id', $conversationId, PDO::PARAM_INT);
    $stmt->bindValue(':message', htmlspecialchars($message), PDO::PARAM_STR);
    $stmt->bindValue(':sender', $sender, PDO::PARAM_STR);
    $stmt->execute();
}
