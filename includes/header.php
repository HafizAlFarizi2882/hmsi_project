<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Detect current directory level to build correct paths
$currentDir = basename(dirname($_SERVER['PHP_SELF']));
$isRoot = ($currentDir == '' || $currentDir == '.' || $currentDir == 'hmsi_sistem');
$isPages = ($currentDir == 'pages');
$isAdmin = ($currentDir == 'admin');

// Build base path
$basePath = '';
if ($isPages || $isAdmin) {
    $basePath = '../';
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' | ' : ''; ?>Himpunan Mahasiswa Sistem Informasi</title>
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
<body class="bg-cream min-h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="bg-primary shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="<?php echo $basePath; ?>index.php" class="flex items-center space-x-2">
                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                            <span class="text-primary font-bold text-lg">H</span>
                        </div>
                        <span class="text-white font-semibold text-lg hidden sm:block">HMSI</span>
                    </a>
                </div>
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-1">
                    <a href="<?php echo $basePath; ?>index.php" class="text-white hover:bg-secondary px-3 py-2 rounded-md text-sm font-medium transition">Home</a>
                    <a href="<?php echo $basePath; ?>pages/tentang.php" class="text-white hover:bg-secondary px-3 py-2 rounded-md text-sm font-medium transition">Tentang</a>
                    <a href="<?php echo $basePath; ?>pages/pendaftaran.php" class="text-white hover:bg-secondary px-3 py-2 rounded-md text-sm font-medium transition">Pendaftaran</a>

                    <?php if (isset($_SESSION["user_id"])): ?>
                        <?php if ($_SESSION["user_role"] == "admin"): ?>
                            <a href="<?php echo $basePath; ?>admin/index.php" class="bg-white text-primary hover:bg-lightgreen px-4 py-2 rounded-md text-sm font-medium transition">Admin</a>
                            <a href="<?php echo $basePath; ?>admin/logout.php" class="text-white hover:bg-secondary px-3 py-2 rounded-md text-sm font-medium transition">Logout</a>
                        <?php else: ?>
                            <a href="<?php echo $basePath; ?>pages/dashboard.php" class="bg-white text-primary hover:bg-lightgreen px-4 py-2 rounded-md text-sm font-medium transition">Dashboard</a>
                            <a href="<?php echo $basePath; ?>pages/logout.php" class="text-white hover:bg-secondary px-3 py-2 rounded-md text-sm font-medium transition">Logout</a>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="<?php echo $basePath; ?>login.php" class="bg-white text-primary hover:bg-lightgreen px-4 py-2 rounded-md text-sm font-medium transition">Login</a>
                    <?php endif; ?>
                </div>
                <!-- Mobile Hamburger -->
                <div class="flex items-center md:hidden">
                    <button id="mobile-menu-btn" class="text-white hover:bg-secondary p-2 rounded-md">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-primary border-t border-secondary">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="<?php echo $basePath; ?>index.php" class="text-white hover:bg-secondary block px-3 py-2 rounded-md text-base font-medium">Home</a>
                <a href="<?php echo $basePath; ?>pages/tentang.php" class="text-white hover:bg-secondary block px-3 py-2 rounded-md text-base font-medium">Tentang</a>
                <a href="<?php echo $basePath; ?>pages/pendaftaran.php" class="text-white hover:bg-secondary block px-3 py-2 rounded-md text-base font-medium">Pendaftaran</a>

                <?php if (isset($_SESSION["user_id"])): ?>
                    <?php if ($_SESSION["user_role"] == "admin"): ?>
                        <a href="<?php echo $basePath; ?>admin/index.php" class="text-white hover:bg-secondary block px-3 py-2 rounded-md text-base font-medium">Admin</a>
                        <a href="<?php echo $basePath; ?>admin/logout.php" class="text-white hover:bg-secondary block px-3 py-2 rounded-md text-base font-medium">Logout</a>
                    <?php else: ?>
                        <a href="<?php echo $basePath; ?>pages/dashboard.php" class="text-white hover:bg-secondary block px-3 py-2 rounded-md text-base font-medium">Dashboard</a>
                        <a href="<?php echo $basePath; ?>pages/logout.php" class="text-white hover:bg-secondary block px-3 py-2 rounded-md text-base font-medium">Logout</a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="<?php echo $basePath; ?>login.php" class="text-white hover:bg-secondary block px-3 py-2 rounded-md text-base font-medium">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <script>
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>
