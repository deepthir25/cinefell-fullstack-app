<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineFeel - <?php echo $pageTitle ?? 'Movie Recommendations Based on Mood'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/cinefeel/assets/css/style.css">
    <link rel="icon" href="/cinefeel/assets/images/logo.png">
    <?php if (isset($isAdminPage) && $isAdminPage): ?>
    <link rel="stylesheet" href="/cinefeel/assets/css/admin.css">
    <?php endif; ?>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/cinefeel/index.php">
                <img src="/cinefeel/assets/images/logo2.jpg" alt="CineFeel Logo" height="40" class="me-2">
                CineFeel<?php echo isset($isAdminPage) ? ' Admin' : ''; ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <?php if (isset($isAdminPage) && $isAdminPage): ?>
                        <!-- Admin Navigation -->
                        <li class="nav-item">
                            <a class="nav-link" href="/cinefeel/admin/index.php"><i class="fas fa-tachometer-alt me-1"></i> Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/cinefeel/admin/users.php"><i class="fas fa-users me-1"></i> Users</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/cinefeel/admin/movies.php"><i class="fas fa-film me-1"></i> Movies</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/cinefeel/admin/moods.php"><i class="fas fa-smile me-1"></i> Moods</a>
                        </li>
                    <?php else: ?>
                        <!-- Regular User Navigation -->
                        <li class="nav-item">
                            <a class="nav-link" href="/cinefeel/index.php">Home</a>
                        </li>
                        <?php if (isLoggedIn()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/cinefeel/mood-selection.php">Get Recommendations</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/cinefeel/favorites.php">My Favorites</a>
                        </li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav">
                    <?php if (isLoggedIn()): ?>
                        <?php if (isAdmin() && !(isset($isAdminPage) && $isAdminPage)): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/cinefeel/admin/"><i class="fas fa-cog me-1"></i> Admin Panel</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i> <?php echo htmlspecialchars($_SESSION['name'] ?? 'User'); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <?php if (!(isset($isAdminPage) && $isAdminPage)): ?>
                                    <li><a class="dropdown-item" href="/cinefeel/profile.php"><i class="fas fa-user me-1"></i> Profile</a></li>
                                <?php endif; ?>
                                <?php if (isset($isAdminPage) && $isAdminPage): ?>
                                    <li><a class="dropdown-item" href="/cinefeel/index.php"><i class="fas fa-external-link-alt me-1"></i> View Site</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="/cinefeel/logout.php"><i class="fas fa-sign-out-alt me-1"></i> Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/cinefeel/login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/cinefeel/register.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container my-4">
