<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "../includes/db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "mahasiswa") {
    header("Location: ../login.php");
    exit;
}

$pageTitle = "Dashboard Anggota";

// Get mahasiswa data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND role = 'mahasiswa'");
$stmt->execute([$_SESSION["user_id"]]);
$user = $stmt->fetch();

if (!$user) {
    session_destroy();
    header("Location: ../login.php");
    exit;
}

require_once "../includes/header.php";
?>

<section class="bg-primary py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-3xl md:text-4xl font-bold text-white mb-3">Dashboard Anggota</h1>
        <p class="text-green-200">Selamat datang, <?php echo htmlspecialchars($user["username"]); ?>!</p>
    </div>
</section>

<section class="py-12 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Status Card -->
        <div class="bg-lightgreen rounded-2xl p-6 mb-8 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-textprimary">Status Keanggotaan</h2>
                    <?php
                    $statusBadge = [
                        'pending' => ['bg-yellow-100 text-yellow-800', 'Menunggu Verifikasi'],
                        'diterima' => ['bg-green-100 text-green-800', 'Anggota Aktif'],
                        'ditolak' => ['bg-red-100 text-red-800', 'Pendaftaran Ditolak']
                    ][$user["status"]];
                    ?>
                    <span class="inline-block mt-2 px-3 py-1 rounded-full text-sm font-medium <?php echo $statusBadge[0]; ?>">
                        <?php echo $statusBadge[1]; ?>
                    </span>
                </div>
                <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Profile Card -->
        <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
            <h2 class="text-xl font-semibold text-textprimary mb-6">Data Profil</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-textsecondary mb-1">Username</label>
                    <p class="text-textprimary font-medium"><?php echo htmlspecialchars($user["username"]); ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-textsecondary mb-1">Email</label>
                    <p class="text-textprimary font-medium"><?php echo htmlspecialchars($user["email"]); ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-textsecondary mb-1">Jurusan</label>
                    <p class="text-textprimary font-medium"><?php echo htmlspecialchars($user["jurusan"]); ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-textsecondary mb-1">Fakultas</label>
                    <p class="text-textprimary font-medium"><?php echo htmlspecialchars($user["fakultas"]); ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-textsecondary mb-1">Jenis Kelamin</label>
                    <p class="text-textprimary font-medium"><?php echo htmlspecialchars($user["jenis_kelamin"]); ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-textsecondary mb-1">Tanggal Daftar</label>
                    <p class="text-textprimary font-medium"><?php echo date('d F Y', strtotime($user["created_at"])); ?></p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-textsecondary mb-1">Alamat</label>
                    <p class="text-textprimary font-medium"><?php echo htmlspecialchars($user["alamat"]); ?></p>
                </div>
            </div>
        </div>

        <!-- Logout Button -->
        <div class="mt-8 text-center">
            <a href="logout.php" class="inline-flex items-center bg-red-600 text-white hover:bg-red-700 px-6 py-3 rounded-lg font-medium transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Logout
            </a>
        </div>
    </div>
</section>

<?php require_once "../includes/footer.php"; ?>
