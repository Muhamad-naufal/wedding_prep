<?php
include 'connection/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = isset($_POST['amount']) ? intval($_POST['amount']) : 0;
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $date = isset($_POST['date']) ? $_POST['date'] : date('Y-m-d');

    // Konversi tipe ke format database
    $typeDb = strtolower($type) === 'masuk' ? 'in' : 'out';

    if ($amount > 0 && $description !== '') {
        $stmt = $conn->prepare("INSERT INTO savings (amount, description, created_at) VALUES (?, ?, ?)");
        $stmt->bind_param('iss', $amount, $description, $date);

        if ($stmt->execute()) {
            header('Location: ../savings.php?success=1');
            exit();
        } else {
            die('Query error: ' . $stmt->error);
        }
    } else {
        header('Location: ../savings.php?error=1');
        exit();
    }
} else {
    header('Location: ../savings.php');
    exit();
}
