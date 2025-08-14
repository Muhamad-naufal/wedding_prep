<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kebutuhan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="main.js" defer></script>
</head>

<body class="bg-pink-50 p-6">
    <a href="index.php" class="inline-block mb-4 bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded transition">
        ← Kembali ke Dashboard
    </a>
    <h1 class="text-3xl font-bold text-pink-500 mb-4">📝 Kebutuhan Lamaran</h1>
    <div class="mb-4">
        <form action="backend/kebutuhan.php" method="POST" class="flex gap-2">
            <input name="name" type="text" placeholder="Tambah kebutuhan..." class="border rounded p-2" required>
            <input name="amount" type="number" placeholder="Nominal" class="border rounded p-2" required>
            <select name="status" class="border rounded p-2">
                <option value="Belum">Belum</option>
                <option value="Selesai">Selesai</option>
            </select>
            <button type="submit" class="bg-pink-500 text-white px-4 py-2 rounded">Tambah</button>
        </form>
    </div>
    <?php
    include_once 'backend/connection/db.php';
    $result = $conn->query("SELECT * FROM needs ORDER BY id DESC");
    ?>
    <ul id="kebutuhan" class="bg-white rounded shadow p-4 divide-y divide-pink-100">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <li class="flex items-center justify-between py-2">
                    <div>
                        <span class="font-semibold text-pink-600"><?= htmlspecialchars($row['name']) ?></span>
                        <span class="ml-2 text-gray-400 text-sm">Rp <?= number_format($row['amount'], 0, ',', '.') ?></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <?php if (strtolower($row['status']) === 'selesai'): ?>
                            <a href="backend/kebutuhan_toggle.php?id=<?= $row['id'] ?>&to=Belum" onclick="return confirm('Ubah status menjadi Belum?')"
                                class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 hover:bg-yellow-100 hover:text-yellow-700 transition cursor-pointer text-decoration-none">
                                Selesai
                            </a>
                        <?php else: ?>
                            <a href="backend/kebutuhan_toggle.php?id=<?= $row['id'] ?>&to=Selesai" onclick="return confirm('Tandai kebutuhan ini sebagai selesai?')"
                                class="px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700 hover:bg-green-100 hover:text-green-700 transition cursor-pointer text-decoration-none">
                                Belum
                            </a>
                        <?php endif; ?>
                        <a href="backend/kebutuhan_hapus.php?id=<?= $row['id'] ?>" onclick="return confirm('Hapus kebutuhan ini?')"
                            class="ml-2 px-2 py-1 rounded text-xs bg-red-100 text-red-600 hover:bg-red-200 transition cursor-pointer text-decoration-none">
                            Hapus
                        </a>
                    </div>
                </li>
            <?php endwhile; ?>
        <?php else: ?>
            <li class="text-gray-400 text-center py-2">Belum ada kebutuhan.</li>
        <?php endif; ?>
    </ul>
</body>

</html>