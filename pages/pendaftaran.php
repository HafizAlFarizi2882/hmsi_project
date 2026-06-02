<?php
$pageTitle = "Pendaftaran";
require_once "../includes/db.php";

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"] ?? "");
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";
    $jurusan = trim($_POST["jurusan"] ?? "");
    $fakultas = trim($_POST["fakultas"] ?? "");
    $alamat = trim($_POST["alamat"] ?? "");
    $jenis_kelamin = $_POST["jenis_kelamin"] ?? "";

    if (empty($email) || empty($username) || empty($password) || empty($jurusan) || empty($fakultas) || empty($alamat) || empty($jenis_kelamin)) {
        $error = "Semua field wajib diisi!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid!";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter!";
    } else {
        $check = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        $check->execute([$email, $username]);
        if ($check->rowCount() > 0) {
            $error = "Email atau username sudah terdaftar!";
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO users (email, username, password, role, jurusan, fakultas, alamat, jenis_kelamin, status, created_at) VALUES (?, ?, ?, 'mahasiswa', ?, ?, ?, ?, 'pending', NOW())");
            if ($stmt->execute([$email, $username, $hash, $jurusan, $fakultas, $alamat, $jenis_kelamin])) {
                $success = "Pendaftaran berhasil! Data Anda telah masuk ke sistem dan menunggu validasi admin.";
            } else {
                $error = "Terjadi kesalahan saat mendaftar. Silakan coba lagi.";
            }
        }
    }
}

require_once "../includes/header.php";
?>

<!-- Registration Hero -->
<section class="bg-primary py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-3xl md:text-4xl font-bold text-white mb-3">Pendaftaran Anggota</h1>
        <p class="text-green-200">Isi formulir di bawah untuk mendaftar sebagai anggota HMSI.</p>
    </div>
</section>

<!-- Registration Form -->
<section class="py-12 bg-white">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php if ($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span><?php echo $success; ?></span>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    <span><?php echo $error; ?></span>
                </div>
            </div>
        <?php endif; ?>

        <div class="bg-lightgreen rounded-2xl p-8 shadow-sm">
            <form method="POST" action="" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-textprimary mb-2">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition" placeholder="email@example.com">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-textprimary mb-2">Username <span class="text-red-500">*</span></label>
                        <input type="text" name="username" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition" placeholder="username_anda">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-textprimary mb-2">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required minlength="6" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition" placeholder="Minimal 6 karakter">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-textprimary mb-2">Jurusan <span class="text-red-500">*</span></label>
                        <input type="text" name="jurusan" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition" placeholder="Sistem Informasi">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-textprimary mb-2">Fakultas <span class="text-red-500">*</span></label>
                        <input type="text" name="fakultas" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition" placeholder="Teknik / Ilmu Komputer">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-textprimary mb-2">Alamat Lengkap <span class="text-red-500">*</span></label>
                    <textarea name="alamat" required rows="3" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition" placeholder="Alamat lengkap Anda..."></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-textprimary mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
                    <div class="flex gap-6">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="jenis_kelamin" value="Laki-laki" required class="w-4 h-4 text-primary focus:ring-primary">
                            <span class="ml-2 text-textsecondary">Laki-laki</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="jenis_kelamin" value="Perempuan" required class="w-4 h-4 text-primary focus:ring-primary">
                            <span class="ml-2 text-textsecondary">Perempuan</span>
                        </label>
                    </div>
                </div>

                <button type="submit" class="w-full bg-primary text-white hover:bg-secondary py-3 rounded-lg font-semibold text-lg transition shadow-md">
                    Kirim Pendaftaran
                </button>
            </form>
        </div>

        <div class="mt-6 text-center text-sm text-textsecondary">
            Sudah mendaftar? <a href="../login.php" class="text-primary hover:text-secondary font-medium">Login di sini</a>
        </div>
    </div>
</section>

<?php require_once "../includes/footer.php"; ?>
