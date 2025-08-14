<?php
include 'connection/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $amount = isset($_POST['amount']) ? intval($_POST['amount']) : 0;
    $status = isset($_POST['status']) ? trim($_POST['status']) : 'Belum';

    if ($name !== '' && $amount > 0) {
        $stmt = $conn->prepare("INSERT INTO needs (name, amount, status) VALUES (?, ?, ?)");
        $stmt->bind_param('sis', $name, $amount, $status);
        if ($stmt->execute()) {
            header('Location: ../needs.php?success=1');
            exit();
        } else {
            header('Location: ../needs.php?error=1');
            exit();
        }
    } else {
        header('Location: ../needs.php?error=1');
        exit();
    }
} else {
    header('Location: ../needs.php');
    exit();
}
