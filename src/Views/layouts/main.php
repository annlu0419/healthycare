<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>抗癌鬥士追蹤系統</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8f9fa; }
        .card { border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-radius: 12px; }
        .navbar { box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .sidebar { min-height: 100vh; }
        .alert-warm { background-color: #fff3cd; border-color: #ffecb5; color: #856404; }
    </style>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<?php if (isset($_SESSION['user_id'])): ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 p-3">
    <div class="container">
        <a class="navbar-brand" href="<?= BASE_URL ?>/dashboard"><i class="fas fa-heartbeat me-2"></i>抗癌日誌</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/dashboard">總覽</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/logs/history">每日紀錄</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/lab">檢驗報告</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/chemo">化療週期</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/profile">個人資料</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/export">匯出報告</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/logout">登出</a></li>
            </ul>
        </div>
    </div>
</nav>
<?php endif; ?>

<div class="container">
    <?php echo $content; ?>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
