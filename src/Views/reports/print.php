<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>病患回診摘要報告</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-size: 12pt; background: #fff; }
        .print-container { max-width: 210mm; margin: 0 auto; padding: 20px; }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
            .print-container { width: 100%; max-width: none; margin: 0; padding: 0; }
        }
        h1, h2 { border-bottom: 2px solid #333; padding-bottom: 5px; }
        .stat-box { border: 1px solid #ccc; padding: 10px; border-radius: 5px; text-align: center; background: #f9f9f9; }
        .stat-val { font-size: 1.5em; font-weight: bold; }
        table { font-size: 0.9em; }
    </style>
</head>
<body>
    <div class="print-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>病患自我追蹤摘要</h1>
            <div class="text-end">
                <p class="mb-0">列印日期: <?php echo date('Y-m-d'); ?></p>
                <p class="mb-0">區間: 近 <?php echo $days; ?> 天</p>
                <button class="btn btn-primary btn-sm no-print" onclick="window.print()">列印此頁</button>
                <a href="<?= BASE_URL ?>/dashboard" class="btn btn-secondary btn-sm no-print">返回</a>
            </div>
        </div>

        <!-- Profile Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <strong>姓名/帳號:</strong> <?php echo $_SESSION['username']; ?> |
                        <strong>診斷:</strong> Stage <?php echo $profile['cancer_stage'] ?? 'Na'; ?> |
                        <strong>TNM:</strong> <?php echo $profile['tnm_stage'] ?? 'Na'; ?> |
                        <strong>目前方案:</strong> <?php echo $profile['regimen_name'] ?? 'Na'; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Key Stats -->
        <div class="row mb-4">
             <div class="col-md-2 col-4 mb-2">
                <div class="stat-box">
                    <div class="stat-val text-danger"><?php echo $stats['max_temp']; ?>°C</div>
                    <small>最高體溫</small>
                </div>
            </div>
            <div class="col-md-2 col-4 mb-2">
                <div class="stat-box">
                    <div class="stat-val text-warning"><?php echo $stats['avg_diarrhea']; ?></div>
                    <small>平均腹瀉(次)</small>
                </div>
            </div>
            <div class="col-md-2 col-4 mb-2">
                <div class="stat-box">
                    <div class="stat-val"><?php echo ($stats['weight_change'] > 0 ? '+' : '') . $stats['weight_change']; ?>kg</div>
                    <small>體重變化</small>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-2">
                <div class="stat-box">
                    <div class="stat-val"><?php echo $stats['max_numbness']; ?>/10</div>
                    <small>最高麻木分數</small>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-2">
                <div class="stat-box">
                    <div class="stat-val"><?php echo $stats['avg_fatigue']; ?>/10</div>
                    <small>平均疲勞分數</small>
                </div>
            </div>
        </div>

        <!-- Recent Logs Table -->
        <h2>每日紀錄明細</h2>
        <table class="table table-bordered table-sm table-striped">
            <thead>
                <tr class="table-dark">
                    <th>日期</th>
                    <th>體重</th>
                    <th>體溫</th>
                    <th>腹瀉</th>
                    <th>噁心</th>
                    <th>麻木</th>
                    <th>疲勞</th>
                    <th>備註</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?php echo substr($log['log_date'], 5); ?></td>
                    <td><?php echo $log['weight'] > 0 ? $log['weight'] : '-'; ?></td>
                    <td class="<?php echo $log['temperature'] >= 38 ? 'text-danger fw-bold' : ''; ?>"><?php echo $log['temperature'] > 0 ? $log['temperature'] : '-'; ?></td>
                    <td><?php echo $log['diarrhea_count'] > 0 ? $log['diarrhea_count'] : '-'; ?></td>
                    <td><?php echo $log['nausea_score'] > 0 ? $log['nausea_score'] : '-'; ?></td>
                    <td><?php echo $log['numbness_score'] > 0 ? $log['numbness_score'] : '-'; ?></td>
                    <td><?php echo $log['fatigue_score'] > 0 ? $log['fatigue_score'] : '-'; ?></td>
                    <td><small><?php echo htmlspecialchars($log['notes'] ?? ''); ?></small></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Chemo History -->
        <?php if (!empty($chemo)): ?>
        <h2 class="mt-4">近期化療紀錄</h2>
        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>週期</th>
                    <th>日期</th>
                    <th>方案</th>
                    <th>備註</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (array_slice($chemo, 0, 5) as $c): ?>
                <tr>
                    <td>#<?php echo $c['cycle_no']; ?></td>
                    <td><?php echo $c['infusion_date']; ?></td>
                    <td><?php echo $c['regimen']; ?></td>
                    <td><?php echo $c['dose_notes']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>

        <div class="mt-5 text-center text-muted">
             <p>本報告由病患自我追蹤系統自動生成，僅供醫療參考。</p>
        </div>
    </div>
</body>
</html>
