<?php
include 'backend/connection/db.php';

// Ambil data tabungan

// Ambil data Tabungan
$sqlSavings = "SELECT * FROM savings";
$resultSavings = $conn->query($sqlSavings);

// Ambil data Kebutuhan
$sqlNeeds = "SELECT * FROM needs";
$resultNeeds = $conn->query($sqlNeeds);

// Ambil data Pengeluaran
$sqlExpenses = "SELECT * FROM expenses";
$resultExpenses = $conn->query($sqlExpenses);

// Data SUM
$sqlTotalSavings = "SELECT SUM(amount) as total FROM savings";
$sqlTotalNeeds = "SELECT SUM(amount) as total FROM needs";
$sqlTotalExpenses = "SELECT SUM(amount) as total FROM expenses";

$resultTotalSavings = $conn->query($sqlTotalSavings);
$rowTotalSavings = $resultTotalSavings ? $resultTotalSavings->fetch_assoc() : ["total" => 0];

$resultTotalNeeds = $conn->query($sqlTotalNeeds);
$rowTotalNeeds = $resultTotalNeeds ? $resultTotalNeeds->fetch_assoc() : ["total" => 0];
$totalNeeds = $rowTotalNeeds["total"] ? $rowTotalNeeds["total"] : 0;

$resultTotalExpenses = $conn->query($sqlTotalExpenses);
$rowTotalExpenses = $resultTotalExpenses ? $resultTotalExpenses->fetch_assoc() : ["total" => 0];
$totalExpenses = $rowTotalExpenses["total"] ? $rowTotalExpenses["total"] : 0;

// Total tabungan = savings - expenses
$totalTabungan = $rowTotalSavings["total"] - $totalExpenses;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>💍 Wedding Prep — Dashboard</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font -->
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

        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(18px);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        .fade-slide-up {
            animation: fadeSlideUp .5s ease both;
        }
    </style>
</head>

<body data-page="dashboard">
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg bg-pink-500 navbar-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.html">💍 Wedding Prep</a>
        </div>
    </nav>

    <main class="container mt-5 fade-slide-up">
        <h1 class="text-3xl font-bold text-pink-500 text-center mb-3">📊 Dashboard Persiapan</h1>
        <p class="text-center text-gray-600 mb-5">Pantau progress & dana lamaran — santai tapi terencana 💖</p>

        <!-- Top Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="bg-white shadow rounded-lg p-4 text-center hover:scale-105 transition-transform">
                    <div class="text-sm text-muted">Total Tabungan</div>
                    <div id="dashboardTotalTabungan" class="text-2xl font-bold text-pink-500">Rp <span><?php echo number_format($totalTabungan, 0, ',', '.'); ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white shadow rounded-lg p-4 text-center">
                    <div class="text-sm text-muted">Total Pemasukan</div>
                    <div id="dashboardTotalMasuk" class="text-2xl font-bold text-green-500">Rp <span><?php echo number_format($rowTotalSavings["total"] ? $rowTotalSavings["total"] : 0, 0, ',', '.'); ?></span></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white shadow rounded-lg p-4 text-center">
                    <div class="text-sm text-muted">Total Pengeluaran</div>
                    <div id="dashboardTotalKeluar" class="text-2xl font-bold text-red-500">Rp <span><?php echo number_format($totalExpenses, 0, ',', '.'); ?></span></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white shadow rounded-lg p-4 text-center">
                    <div class="text-sm text-muted">Progress Persiapan</div>
                    <div id="dashboardPreparationProgress" class="text-2xl font-bold text-indigo-600">
                        <?php
                        $progress = 0;
                        if ($totalNeeds > 0) {
                            // Query ulang data needs agar tidak habis
                            $resultNeedsProgress = $conn->query("SELECT status FROM needs");
                            $done = 0;
                            while ($need = $resultNeedsProgress->fetch_assoc()) {
                                if (isset($need['status']) && strtolower($need['status']) !== 'belum') {
                                    $done++;
                                }
                            }
                            $progress = round(($done / $resultNeedsProgress->num_rows) * 100);
                        }
                        echo $progress . '%';
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Tabungan -->
        <section class="bg-white shadow rounded-lg p-4 mb-4">
            <p class="fw-semibold text-gray-700">Progress Tabungan</p>
            <div class="w-full bg-gray-200 rounded-full h-4 mt-2 overflow-hidden">
                <?php
                $percent = 0;
                if ($totalNeeds > 0) {
                    $percent = round((($rowTotalSavings["total"] ? $rowTotalSavings["total"] : 0) / $totalNeeds) * 100);
                }
                ?>
                <div id="dashboardSavingsBar" class="h-4 rounded-full" style="background:linear-gradient(90deg,#f472b6,#fbcfe8); width:<?php echo $percent; ?>%"></div>
            </div>
            <div class="d-flex justify-content-between mt-2 small text-muted">
                <div id="dashboardTargetLabel">Target: Rp <?php echo number_format($totalNeeds, 0, ',', '.'); ?></div>
                <div id="dashboardSavingsPercent">
                    <?php
                    $percent = 0;
                    if ($totalNeeds > 0) {
                        $percent = round(($totalTabungan / $totalNeeds) * 100);
                    }
                    echo $percent . '%';
                    ?>
                </div>
            </div>
        </section>

        <!-- Small summaries: Kebutuhan & Biaya -->
        <div class="row g-4">
            <div class="col-md-6">
                <div class="bg-white shadow rounded-lg p-4">
                    <h5 class="text-pink-500 fw-bold">To-Do Kebutuhan</h5>
                    <ul id="dashboardNeedsList" class="list-group list-group-flush mt-3">
                        <?php
                        // Tampilkan 5 kebutuhan teratas
                        $sqlNeedsList = "SELECT * FROM needs ORDER BY id DESC LIMIT 5";
                        $resultNeedsList = $conn->query($sqlNeedsList);
                        if ($resultNeedsList && $resultNeedsList->num_rows > 0) {
                            while ($need = $resultNeedsList->fetch_assoc()) {
                                echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
                                echo htmlspecialchars($need['name']);
                                if (isset($need['status']) && strtolower($need['status']) == 'selesai') {
                                    echo '<span class="badge bg-success">Selesai</span>';
                                } else {
                                    echo '<span class="badge bg-warning text-dark">Belum</span>';
                                }
                                echo '</li>';
                            }
                        } else {
                            echo '<li class="list-group-item">Belum ada kebutuhan.</li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>

            <!-- Ringkasan Kebutuhan -->
            <div class="col-md-6">
                <div class="bg-white shadow rounded-lg p-4">
                    <h5 class="text-pink-500 fw-bold">Ringkasan Biaya</h5>
                    <div class="mt-3">
                        <div>Target: <span id="dashboardBudgetTarget">Rp <?php echo number_format($totalNeeds, 0, ',', '.'); ?></span></div>
                        <div>Total Tabungan: <span id="dashboardBudgetTotalSavings">Rp <?php echo number_format($rowTotalSavings["total"] ? $rowTotalSavings["total"] : 0, 0, ',', '.'); ?></span></div>
                        <div>Terpakai: <span id="dashboardBudgetSpent">Rp <?php echo number_format($totalExpenses, 0, ',', '.'); ?></span></div>
                        <div>Sisa: <span id="dashboardBudgetRemain">Rp <?php echo number_format($totalTabungan, 0, ',', '.'); ?></span></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <a class="btn bg-pink-500 text-white" href="savings.php">💰 Kelola Tabungan</a>
            <a class="btn btn-outline-primary ms-2" href="needs.php">📋 Kebutuhan</a>
            <a class="btn btn-outline-secondary ms-2" href="budgets.php">💸 Biaya</a>
        </div>
    </main>

    <footer class="text-center py-4 mt-5 text-gray-500">&copy; 2025 Wedding Prep by DMG WEDDING 💖</footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="main.js"></script>
</body>

</html>