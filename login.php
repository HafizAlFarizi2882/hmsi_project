<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "includes/db.php";

// Redirect if already logged in
if (isset($_SESSION["user_id"])) {
    if ($_SESSION["user_role"] == "admin") {
        header("Location: admin/index.php");
    } else {
        header("Location: pages/dashboard.php");
    }
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if (empty($username) || empty($password)) {
        $error = "Username dan password wajib diisi!";
    } else {
        $stmt = $pdo->prepare("SELECT id, username, password, role, status FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user["password"])) {
            if ($user["role"] == "admin") {
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["user_username"] = $user["username"];
                $_SESSION["user_role"] = $user["role"];
                header("Location: admin/index.php");
                exit;
            } else {
                if ($user["status"] == "pending") {
                    $error = "Pendaftaran Anda masih menunggu verifikasi admin. Silakan tunggu konfirmasi.";
                } elseif ($user["status"] == "ditolak") {
                    $error = "Maaf, pendaftaran Anda telah ditolak oleh admin.";
                } elseif ($user["status"] == "diterima") {
                    $_SESSION["user_id"] = $user["id"];
                    $_SESSION["user_username"] = $user["username"];
                    $_SESSION["user_role"] = $user["role"];
                    header("Location: pages/dashboard.php");
                    exit;
                }
            }
        } else {
            $error = "Username atau password salah!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | HMSI</title>
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
<body class="bg-cream min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md px-4">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-white font-bold text-2xl">H</span>
                </div>
                <h1 class="text-2xl font-bold text-textprimary">Login HMSI</h1>
                <p class="text-textsecondary text-sm mt-1">Himpunan Mahasiswa Sistem Informasi</p>
            </div>

            <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        <span><?php echo $error; ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <form method="POST" action="" class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-textprimary mb-2">Username</label>
                    <input type="text" name="username" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition" placeholder="Masukkan username">
                </div>
                <div>
                    <label class="block text-sm font-medium text-textprimary mb-2">Password</label>
                    <input type="password" name="password" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition" placeholder="Masukkan password">
                </div>
                <button type="submit" class="w-full bg-primary text-white hover:bg-secondary py-3 rounded-lg font-semibold transition shadow-md">
                    Masuk
                </button>
            </form>

            <div class="mt-6 text-center space-y-2">
                <p class="text-sm text-textsecondary">Belum punya akun? <a href="pages/pendaftaran.php" class="text-primary hover:text-secondary font-medium">Daftar di sini</a></p>
                <a href="index.php" class="text-primary hover:text-secondary text-sm font-medium transition block">&larr; Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</body>
</html>
