<?php
include 'connection/db.php';

if (isset($_GET['id']) && isset($_GET['to'])) {
    $id = intval($_GET['id']);
    $to = ($_GET['to'] === 'Selesai') ? 'Selesai' : 'Belum';
    $stmt = $conn->prepare("UPDATE needs SET status=? WHERE id=?");
    $stmt->bind_param('si', $to, $id);
    $stmt->execute();
}
header('Location: ../needs.php');
exit();
