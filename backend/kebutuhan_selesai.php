<?php
include 'connection/db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("UPDATE needs SET status='selesai' WHERE id=?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
}
header('Location: ../needs.php');
exit();
