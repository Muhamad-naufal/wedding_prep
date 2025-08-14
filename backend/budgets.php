<?php
include 'connection/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $amount = isset($_POST['amount']) ? intval($_POST['amount']) : 0;
    $date = isset($_POST['date']) ? $_POST['date'] : date('Y-m-d');

    if ($name !== '' && $amount > 0) {
        $stmt = $conn->prepare("INSERT INTO expenses (name, amount, created_at) VALUES (?, ?, ?)");
        $stmt->bind_param('sis', $name, $amount, $date);
        if ($stmt->execute()) {
            header('Location: ../budgets.php?success=1');
            exit();
        } else {
            header('Location: ../budgets.php?error=1');
            exit();
        }
    } else {
        header('Location: ../budgets.php?error=1');
        exit();
    }
} else {
    header('Location: ../budgets.php');
    exit();
}
