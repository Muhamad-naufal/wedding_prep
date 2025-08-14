<?php
include 'backend/connection/db.php';

// Ambil data tabungan
$result = $conn->query("SELECT id, created_at, description, amount FROM savings ORDER BY created_at DESC");
$savings = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>💰 Tabungan — Wedding Prep</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --pink: #f472b6;
            --bg: #fff7fa;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg);
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-pink-500 navbar-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">💍 Wedding Prep</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="needs.php">Kebutuhan</a></li>
                    <li class="nav-item"><a class="nav-link" href="budget.php">Biaya</a></li>
                    <li class="nav-item"><a class="nav-link active" href="savings.php">Tabungan</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container mt-5">
        <h1 class="text-3xl fw-bold text-pink-500 text-center">💰 Manage Tabungan</h1>
        <p class="text-center text-gray-600">Catat pemasukan & pengeluaran — data tersimpan di database.</p>

        <!-- Form input -->
        <section class="bg-white p-3 rounded shadow-sm mt-4">
            <form action="backend/tabungan.php" method="POST" class="row g-2">
                <div class="col-md-3"><input type="date" name="date" class="form-control" required></div>
                <div class="col-md-4"><input type="text" name="description" class="form-control" placeholder="Keterangan" required></div>
                <div class="col-md-2"><input type="number" name="amount" class="form-control" placeholder="Nominal" required></div>
                <div class="col-md-1"><button class="btn bg-pink-500 text-white w-100" type="submit">Simpan</button></div>
            </form>
        </section>

        <!-- Tabel -->
        <section class="bg-white p-3 rounded shadow-sm mt-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="text-pink-500 fw-bold">Riwayat Transaksi</h5>
                <div class="small text-muted">Total: <?= count($savings) ?></div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                            <th>Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($savings) > 0): ?>
                            <?php foreach ($savings as $i => $row): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td>
                                        <?php
                                        $dt = strtotime($row['created_at']);
                                        setlocale(LC_TIME, 'id_ID.utf8', 'id_ID', 'Indonesian_indonesia');
                                        // Format: 20 Juli 2025
                                        echo strftime('%e %B %Y', $dt);
                                        ?>
                                    </td>
                                    <td><?= htmlspecialchars($row['description']) ?></td>

                                    <td>Rp <?= number_format($row['amount'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">Belum ada data</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <footer class="text-center py-4 mt-5 text-gray-500">&copy; 2025 Wedding Prep</footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>