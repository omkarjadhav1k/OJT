<?php
$error        = $error ?? '';
$success      = $success ?? '';
$oldEmail     = $oldEmail ?? '';
$remembered   = !empty($remembered);
$loggedInUser = $loggedInUser ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - The Bakery</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.24.0/dist/tabler-icons.min.css">
    <link rel="stylesheet" href="<?= clean(baseUrl('Public/css/login.css')) ?>">
</head>
<body>
    <div class="login-page">
        <div class="bakery-photo-panel">
            <img src="<?= clean(baseUrl('Public/uploads/bakery-bg.jpg')) ?>" alt="The Bakery display counter">
        </div>

        <div class="cream-split-panel">
            <div class="decor-blob"></div>
        </div>

        <div class="login-card-wrap">
            <div class="login-card">
                <div class="card-emblem">
                    <span class="emblem-line"></span>
                    <i class="ti ti-chef-hat chef-hat-icon"></i>
                    <span class="emblem-line"></span>
                </div>

                <h1 class="login-title">Welcome Back!</h1>
                <p class="login-subtitle">Login to manage your bakery, orders and inventory.</p>

                <?php if ($error !== ''): ?>
                    <div class="alert-box alert-error"><?= clean($error) ?></div>
                <?php elseif ($success !== ''): ?>
                    <div class="alert-box alert-success">
                        <span><?= clean($success) ?></span>
                        <?php if ($loggedInUser): ?>
                            <a href="<?= clean(baseUrl('logout')) ?>" class="logout-link">Logout</a>
                        <?php endif; ?>
                    </div>
                <?php elseif ($loggedInUser): ?>
                    <div class="alert-box alert-info">
                        <span>Logged in as <strong><?= clean($loggedInUser['user_name']) ?></strong></span>
                        <a href="<?= clean(baseUrl('logout')) ?>" class="logout-link">Logout</a>
                    </div>
                <?php endif; ?>

                <div id="clientAlert" class="alert-box is-hidden"></div>

                <form id="loginForm" class="login-form" action="<?= clean(baseUrl('login')) ?>" method="POST" novalidate>
                    <div class="input-group">
                        <span class="input-icon"><i class="ti ti-mail"></i></span>
                        <input type="email" id="user_email" name="user_email" placeholder="Enter your email" value="<?= clean($oldEmail) ?>" required>
                    </div>

                    <div class="input-group password-group">
                        <span class="input-icon"><i class="ti ti-lock"></i></span>
                        <input type="password" id="user_password" name="user_password" placeholder="Enter password" required>
                        <button type="button" id="togglePassword" class="toggle-password">
                            <i class="ti ti-eye-off icon-eye-closed"></i>
                            <i class="ti ti-eye icon-eye-open"></i>
                        </button>
                    </div>

                    <div class="form-options">
                        <label class="remember-label">
                            <input type="checkbox" id="remember_me" name="remember_me" value="1" <?= $remembered ? 'checked' : '' ?>>
                            <span>Remember me</span>
                        </label>
                        <a href="#" id="forgotPasswordLink" class="forgot-link">Forgot password?</a>
                    </div>

                    <button type="submit" class="login-btn">
                        <span>Login</span>
                        <i class="ti ti-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="<?= clean(baseUrl('Public/js/login.js')) ?>"></script>
</body>
</html>
