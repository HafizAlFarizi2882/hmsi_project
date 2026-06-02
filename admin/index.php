<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "../includes/db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "admin") {
    header("Location: ../login.php");
    exit;
}

$pageTitle = "Dashboard";

// Statistik
$total_mahasiswa = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'mahasiswa'")->fetchColumn();
$pending = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'mahasiswa' AND status = 'pending'")->fetchColumn();
$diterima = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'mahasiswa' AND status = 'diterima'")->fetchColumn();
$ditolak = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'mahasiswa' AND status = 'ditolak'")->fetchColumn();

// Data terbaru
$recent = $pdo->query("SELECT id, username, email, jurusan, status, created_at FROM users WHERE role = 'mahasiswa' ORDER BY created_at DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> | Admin HMSI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1B5E20',
                        secondary: '#2E7D32',
                        lightgreen: '#E8F5E9',
                        cream: '#FFF8E1',
                        textprimary: '#1A1A1A',
                        textsecondary: '#4A4A4A',
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body{font-family:'Inter',sans-serif;}</style>
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Admin Navbar -->
    <nav class="bg-primary shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="index.php" class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center">
                            <span class="text-primary font-bold">A</span>
                        </div>
                        <span class="text-white font-semibold">Admin HMSI</span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-green-200 text-sm hidden sm:block">Halo, <?php echo htmlspecialchars($_SESSION["user_username"]); ?></span>
                    <a href="logout.php" class="bg-white/10 hover:bg-white/20 text-white px-3 py-1.5 rounded-md text-sm transition">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar + Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="p-4 bg-primary text-white">
                        <h3 class="font-semibold">Menu Admin</h3>
                    </div>
                    <div class="p-2">
                        <a href="index.php" class="block px-4 py-2 rounded-lg bg-lightgreen text-primary font-medium">Dashboard</a>
                        <a href="users.php" class="block px-4 py-2 rounded-lg text-textsecondary hover:bg-gray-50 transition">Manajemen Anggota</a>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-3">
                <h1 class="text-2xl font-bold text-textprimary mb-6">Dashboard</h1>

                <!-- Stats Cards -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-white rounded-xl p-5 shadow-sm">
                        <div class="text-3xl font-bold text-primary"><?php echo $total_mahasiswa; ?></div>
                        <div class="text-textsecondary text-sm">Total Pendaftar</div>
                    </div>
                    <div class="bg-white rounded-xl p-5 shadow-sm">
                        <div class="text-3xl font-bold text-yellow-600"><?php echo $pending; ?></div>
                        <div class="text-textsecondary text-sm">Menunggu</div>
                    </div>
                    <div class="bg-white rounded-xl p-5 shadow-sm">
                        <div class="text-3xl font-bold text-green-600"><?php echo $diterima; ?></div>
                        <div class="text-textsecondary text-sm">Diterima</div>
                    </div>
                    <div class="bg-white rounded-xl p-5 shadow-sm">
                        <div class="text-3xl font-bold text-red-600"><?php echo $ditolak; ?></div>
                        <div class="text-textsecondary text-sm">Ditolak</div>
                    </div>
                </div>

                <!-- Recent Registrations -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-textprimary">Pendaftaran Terbaru</h2>
                        <a href="users.php" class="text-primary hover:text-secondary text-sm font-medium transition">Lihat Semua &rarr;</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-medium text-textsecondary uppercase">Username</th>
                                    <th class="px-6 py-3 text-xs font-medium text-textsecondary uppercase">Email</th>
                                    <th class="px-6 py-3 text-xs font-medium text-textsecondary uppercase">Jurusan</th>
                                    <th class="px-6 py-3 text-xs font-medium text-textsecondary uppercase">Status</th>
                                    <th class="px-6 py-3 text-xs font-medium text-textsecondary uppercase">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php while ($row = $recent->fetch()): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-textprimary"><?php echo htmlspecialchars($row["username"]); ?></td>
                                    <td class="px-6 py-4 text-sm text-textsecondary"><?php echo htmlspecialchars($row["email"]); ?></td>
                                    <td class="px-6 py-4 text-sm text-textsecondary"><?php echo htmlspecialchars($row["jurusan"]); ?></td>
                                    <td class="px-6 py-4">
                                        <?php
                                        $badgeClass = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'diterima' => 'bg-green-100 text-green-800',
                                            'ditolak' => 'bg-red-100 text-red-800'
                                        ][$row["status"]] ?? 'bg-gray-100 text-gray-800';
                                        ?>
                                        <span class="px-2 py-1 rounded-full text-xs font-medium <?php echo $badgeClass; ?>">
                                            <?php echo ucfirst($row["status"]); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-textsecondary"><?php echo date('d/m/Y', strtotime($row["created_at"])); ?></td>
                                </tr>
                                <?php endwhile; ?>
                                <?php if ($recent->rowCount() == 0): ?>
                                <tr><td colspan="5" class="px-6 py-8 text-center text-textsecondary">Belum ada pendaftaran</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
