<?php
include 'connection/db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM needs WHERE id=?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
}
header('Location: ../needs.php');
exit();
