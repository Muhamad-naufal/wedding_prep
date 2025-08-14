<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Anggaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="main.js" defer></script>
</head>

<body class="bg-pink-50 p-6">
    <a href="index.php" class="inline-block mb-4 bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded transition">
        ← Kembali ke Dashboard
    </a>
    <h1 class="text-3xl font-bold text-pink-500 mb-4">💰 Anggaran Lamaran</h1>
    <div class="mb-4">
        <form action="backend/budgets.php" method="POST" class="flex gap-2">
            <input name="name" type="text" placeholder="Nama pengeluaran" class="border rounded p-2" required>
            <input name="amount" type="number" placeholder="Jumlah" class="border rounded p-2" required>
            <input name="date" type="date" class="border rounded p-2" value="<?= date('Y-m-d') ?>">
            <button type="submit" class="bg-pink-500 text-white px-4 py-2 rounded">Tambah</button>
        </form>
    </div>
    <ul id="ppengeluaran" class="bg-white rounded shadow p-4">
        <?php
        include_once 'backend/connection/db.php';
        $sql = "SELECT * FROM expenses";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $dt = strtotime($row['created_at']);
                setlocale(LC_TIME, 'id_ID.utf8', 'id_ID', 'Indonesian_indonesia');
                $tgl = strftime('%e %B %Y', $dt);
                echo "<li class='border-b py-2'>{$row['name']} - Rp " . number_format($row['amount'], 0, ',', '.') . " ($tgl)</li>";
            }
        } else {
            echo "<li class='py-2'>Belum ada pengeluaran</li>";
        }
        ?>
    </ul>
</body>

</html>