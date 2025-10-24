<?php
session_start();
require_once '../database.php';

header('Content-Type: application/json');

$action = $_GET['action'];

if ($action === 'start_conversation') {
    if (!isset($_SESSION['conversation_id'])) {
        $userIp = $_SERVER['REMOTE_ADDR'];
        $stmt = $db->prepare("INSERT INTO conversations (user_ip) VALUES (:user_ip)");
        $stmt->bindValue(':user_ip', $userIp, PDO::PARAM_STR);
        $stmt->execute();
        $_SESSION['conversation_id'] = $db->lastInsertId();
    }
    echo json_encode(['conversation_id' => $_SESSION['conversation_id']]);
}

if ($action === 'get_messages') {
    if (!isset($_SESSION['conversation_id'])) {
        http_response_code(400);
        exit;
    }
    $conversationId = $_SESSION['conversation_id'];
    $stmt = $db->prepare("SELECT * FROM messages WHERE conversation_id = :conversation_id");
    $stmt->bindValue(':conversation_id', $conversationId, PDO::PARAM_INT);
    $stmt->execute();
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

if ($action === 'send_message') {
    if (!isset($_SESSION['conversation_id'])) {
        http_response_code(400);
        exit;
    }
    $conversationId = $_SESSION['conversation_id'];
    $message = $_POST['message'];
    $sender = $_POST['sender'];

    $stmt = $db->prepare("INSERT INTO messages (conversation_id, message, sender) VALUES (:conversation_id, :message, :sender)");
    $stmt->bindValue(':conversation_id', $conversationId, PDO::PARAM_INT);
    $stmt->bindValue(':message', htmlspecialchars($message), PDO::PARAM_STR);
    $stmt->bindValue(':sender', $sender, PDO::PARAM_STR);
    $stmt->execute();
}
