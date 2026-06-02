<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "../includes/db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "admin") {
    header("Location: ../login.php");
    exit;
}

$pageTitle = "Manajemen Anggota";
$success = "";
$error = "";

// --- DELETE ---
if (isset($_GET["delete"])) {
    $id = intval($_GET["delete"]);
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role = 'mahasiswa'");
    if ($stmt->execute([$id])) {
        $success = "Data mahasiswa berhasil dihapus.";
    } else {
        $error = "Gagal menghapus data.";
    }
}

// --- UPDATE STATUS ---
if (isset($_GET["status"]) && isset($_GET["id"])) {
    $id = intval($_GET["id"]);
    $status = $_GET["status"];
    if (in_array($status, ["pending", "diterima", "ditolak"])) {
        $stmt = $pdo->prepare("UPDATE users SET status = ? WHERE id = ? AND role = 'mahasiswa'");
        if ($stmt->execute([$status, $id])) {
            $success = "Status berhasil diperbarui.";
        } else {
            $error = "Gagal memperbarui status.";
        }
    }
}

// --- CREATE / EDIT ---
$editMode = false;
$editData = [];
if (isset($_GET["edit"])) {
    $editMode = true;
    $id = intval($_GET["edit"]);
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND role = 'mahasiswa'");
    $stmt->execute([$id]);
    $editData = $stmt->fetch();
    if (!$editData) {
        $editMode = false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"] ?? "";
    $email = trim($_POST["email"] ?? "");
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";
    $jurusan = trim($_POST["jurusan"] ?? "");
    $fakultas = trim($_POST["fakultas"] ?? "");
    $alamat = trim($_POST["alamat"] ?? "");
    $jenis_kelamin = $_POST["jenis_kelamin"] ?? "";
    $status = $_POST["status"] ?? "pending";

    if (empty($email) || empty($username) || empty($jurusan) || empty($fakultas) || empty($alamat) || empty($jenis_kelamin)) {
        $error = "Semua field wajib diisi!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid!";
    } else {
        if ($id) {
            // UPDATE
            if (!empty($password)) {
                $hash = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare("UPDATE users SET email=?, username=?, password=?, jurusan=?, fakultas=?, alamat=?, jenis_kelamin=?, status=? WHERE id=? AND role='mahasiswa'");
                $stmt->execute([$email, $username, $hash, $jurusan, $fakultas, $alamat, $jenis_kelamin, $status, $id]);
            } else {
                $stmt = $pdo->prepare("UPDATE users SET email=?, username=?, jurusan=?, fakultas=?, alamat=?, jenis_kelamin=?, status=? WHERE id=? AND role='mahasiswa'");
                $stmt->execute([$email, $username, $jurusan, $fakultas, $alamat, $jenis_kelamin, $status, $id]);
            }
            $success = "Data mahasiswa berhasil diperbarui.";
            $editMode = false;
        } else {
            // CREATE
            if (empty($password) || strlen($password) < 6) {
                $error = "Password minimal 6 karakter!";
            } else {
                $check = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
                $check->execute([$email, $username]);
                if ($check->rowCount() > 0) {
                    $error = "Email atau username sudah terdaftar!";
                } else {
                    $hash = password_hash($password, PASSWORD_BCRYPT);
                    $stmt = $pdo->prepare("INSERT INTO users (email, username, password, role, jurusan, fakultas, alamat, jenis_kelamin, status, created_at) VALUES (?, ?, ?, 'mahasiswa', ?, ?, ?, ?, ?, NOW())");
                    $stmt->execute([$email, $username, $hash, $jurusan, $fakultas, $alamat, $jenis_kelamin, $status]);
                    $success = "Data mahasiswa berhasil ditambahkan.";
                }
            }
        }
    }
}

// Search & Filter
$search = $_GET["search"] ?? "";
$filter_status = $_GET["filter_status"] ?? "";

$sql = "SELECT * FROM users WHERE role = 'mahasiswa'";
$params = [];
if (!empty($search)) {
    $sql .= " AND (username LIKE ? OR email LIKE ? OR jurusan LIKE ? OR fakultas LIKE ?)";
    $like = "%$search%";
    $params = [$like, $like, $like, $like];
}
if (!empty($filter_status)) {
    $sql .= " AND status = ?";
    $params[] = $filter_status;
}
$sql .= " ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$users = $stmt->fetchAll();
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

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="p-4 bg-primary text-white">
                        <h3 class="font-semibold">Menu Admin</h3>
                    </div>
                    <div class="p-2">
                        <a href="index.php" class="block px-4 py-2 rounded-lg text-textsecondary hover:bg-gray-50 transition">Dashboard</a>
                        <a href="users.php" class="block px-4 py-2 rounded-lg bg-lightgreen text-primary font-medium">Manajemen Anggota</a>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-3">
                <h1 class="text-2xl font-bold text-textprimary mb-6">Manajemen Anggota</h1>

                <?php if ($success): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6"><?php echo $success; ?></div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6"><?php echo $error; ?></div>
                <?php endif; ?>

                <!-- Form Tambah/Edit -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
                    <h2 class="text-lg font-semibold text-textprimary mb-4"><?php echo $editMode ? 'Edit Data Mahasiswa' : 'Tambah Data Mahasiswa'; ?></h2>
                    <form method="POST" action="" class="space-y-4">
                        <input type="hidden" name="id" value="<?php echo $editMode ? $editData['id'] : ''; ?>">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-textprimary mb-1">Email <span class="text-red-500">*</span></label>
                                <input type="email" name="email" required value="<?php echo $editMode ? htmlspecialchars($editData['email']) : ''; ?>" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-textprimary mb-1">Username <span class="text-red-500">*</span></label>
                                <input type="text" name="username" required value="<?php echo $editMode ? htmlspecialchars($editData['username']) : ''; ?>" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-textprimary mb-1">Password <?php echo $editMode ? '(Kosongkan jika tidak diubah)' : '<span class="text-red-500">*</span> (Min 6 karakter)'; ?></label>
                            <input type="password" name="password" <?php echo $editMode ? '' : 'required minlength="6"'; ?> class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-textprimary mb-1">Jurusan <span class="text-red-500">*</span></label>
                                <input type="text" name="jurusan" required value="<?php echo $editMode ? htmlspecialchars($editData['jurusan']) : ''; ?>" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-textprimary mb-1">Fakultas <span class="text-red-500">*</span></label>
                                <input type="text" name="fakultas" required value="<?php echo $editMode ? htmlspecialchars($editData['fakultas']) : ''; ?>" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-textprimary mb-1">Alamat <span class="text-red-500">*</span></label>
                            <textarea name="alamat" required rows="2" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition"><?php echo $editMode ? htmlspecialchars($editData['alamat']) : ''; ?></textarea>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-textprimary mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                                <select name="jenis_kelamin" required class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition">
                                    <option value="">Pilih</option>
                                    <option value="Laki-laki" <?php echo ($editMode && $editData['jenis_kelamin'] == 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                                    <option value="Perempuan" <?php echo ($editMode && $editData['jenis_kelamin'] == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-textprimary mb-1">Status <span class="text-red-500">*</span></label>
                                <select name="status" required class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition">
                                    <option value="pending" <?php echo ($editMode && $editData['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                    <option value="diterima" <?php echo ($editMode && $editData['status'] == 'diterima') ? 'selected' : ''; ?>>Diterima</option>
                                    <option value="ditolak" <?php echo ($editMode && $editData['status'] == 'ditolak') ? 'selected' : ''; ?>>Ditolak</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <button type="submit" class="bg-primary text-white hover:bg-secondary px-6 py-2 rounded-lg font-medium transition">
                                <?php echo $editMode ? 'Simpan Perubahan' : 'Tambah Anggota'; ?>
                            </button>
                            <?php if ($editMode): ?>
                                <a href="users.php" class="bg-gray-200 text-gray-700 hover:bg-gray-300 px-6 py-2 rounded-lg font-medium transition">Batal</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>

                <!-- Search & Filter -->
                <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
                    <form method="GET" action="" class="flex flex-col md:flex-row gap-3">
                        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Cari username, email, jurusan..." class="flex-1 px-4 py-2 rounded-lg border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition">
                        <select name="filter_status" class="px-4 py-2 rounded-lg border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition">
                            <option value="">Semua Status</option>
                            <option value="pending" <?php echo $filter_status == 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="diterima" <?php echo $filter_status == 'diterima' ? 'selected' : ''; ?>>Diterima</option>
                            <option value="ditolak" <?php echo $filter_status == 'ditolak' ? 'selected' : ''; ?>>Ditolak</option>
                        </select>
                        <button type="submit" class="bg-primary text-white hover:bg-secondary px-6 py-2 rounded-lg font-medium transition">Cari</button>
                        <?php if ($search || $filter_status): ?>
                            <a href="users.php" class="bg-gray-200 text-gray-700 hover:bg-gray-300 px-6 py-2 rounded-lg font-medium transition text-center">Reset</a>
                        <?php endif; ?>
                    </form>
                </div>

                <!-- Data Table -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-textprimary">Data Mahasiswa (<?php echo count($users); ?>)</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-xs font-medium text-textsecondary uppercase">ID</th>
                                    <th class="px-4 py-3 text-xs font-medium text-textsecondary uppercase">Username</th>
                                    <th class="px-4 py-3 text-xs font-medium text-textsecondary uppercase">Email</th>
                                    <th class="px-4 py-3 text-xs font-medium text-textsecondary uppercase">Jurusan</th>
                                    <th class="px-4 py-3 text-xs font-medium text-textsecondary uppercase">Fakultas</th>
                                    <th class="px-4 py-3 text-xs font-medium text-textsecondary uppercase">JK</th>
                                    <th class="px-4 py-3 text-xs font-medium text-textsecondary uppercase">Status</th>
                                    <th class="px-4 py-3 text-xs font-medium text-textsecondary uppercase">Tanggal</th>
                                    <th class="px-4 py-3 text-xs font-medium text-textsecondary uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php foreach ($users as $u): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-textsecondary"><?php echo $u["id"]; ?></td>
                                    <td class="px-4 py-3 text-sm font-medium text-textprimary"><?php echo htmlspecialchars($u["username"]); ?></td>
                                    <td class="px-4 py-3 text-sm text-textsecondary"><?php echo htmlspecialchars($u["email"]); ?></td>
                                    <td class="px-4 py-3 text-sm text-textsecondary"><?php echo htmlspecialchars($u["jurusan"]); ?></td>
                                    <td class="px-4 py-3 text-sm text-textsecondary"><?php echo htmlspecialchars($u["fakultas"]); ?></td>
                                    <td class="px-4 py-3 text-sm text-textsecondary"><?php echo $u["jenis_kelamin"]; ?></td>
                                    <td class="px-4 py-3">
                                        <?php
                                        $badgeClass = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'diterima' => 'bg-green-100 text-green-800',
                                            'ditolak' => 'bg-red-100 text-red-800'
                                        ][$u["status"]] ?? 'bg-gray-100 text-gray-800';
                                        ?>
                                        <span class="px-2 py-1 rounded-full text-xs font-medium <?php echo $badgeClass; ?>">
                                            <?php echo ucfirst($u["status"]); ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-textsecondary"><?php echo date('d/m/Y', strtotime($u["created_at"])); ?></td>
                                    <td class="px-4 py-3">
                                        <div class="flex flex-wrap gap-1">
                                            <a href="users.php?edit=<?php echo $u['id']; ?>" class="bg-blue-100 text-blue-700 hover:bg-blue-200 px-2 py-1 rounded text-xs font-medium transition">Edit</a>
                                            <?php if ($u["status"] != "diterima"): ?>
                                                <a href="users.php?status=diterima&id=<?php echo $u['id']; ?>" class="bg-green-100 text-green-700 hover:bg-green-200 px-2 py-1 rounded text-xs font-medium transition" onclick="return confirm('Terima pendaftaran ini?')">Terima</a>
                                            <?php endif; ?>
                                            <?php if ($u["status"] != "ditolak"): ?>
                                                <a href="users.php?status=ditolak&id=<?php echo $u['id']; ?>" class="bg-red-100 text-red-700 hover:bg-red-200 px-2 py-1 rounded text-xs font-medium transition" onclick="return confirm('Tolak pendaftaran ini?')">Tolak</a>
                                            <?php endif; ?>
                                            <a href="users.php?delete=<?php echo $u['id']; ?>" class="bg-gray-100 text-gray-700 hover:bg-gray-200 px-2 py-1 rounded text-xs font-medium transition" onclick="return confirm('Hapus data ini?')">Hapus</a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($users)): ?>
                                <tr><td colspan="9" class="px-6 py-8 text-center text-textsecondary">Tidak ada data mahasiswa</td></tr>
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
