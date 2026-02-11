<div class="row">
    <!-- Sidebar / Profile Summary -->
    <div class="col-md-3 mb-4">
        <div class="card bg-light">
            <div class="card-body">
                <h5 class="card-title">病患摘要</h5>
                <?php if($profile): ?>
                <p><strong>診斷:</strong> Stage <?php echo $profile['cancer_stage']; ?></p>
                <p><strong>方案:</strong> <?php echo $profile['regimen_name']; ?></p>
                <p><strong>標記:</strong> <?php echo $profile['biomarkers']; ?></p>
                <?php else: ?>
                <p class="text-muted">請先至個人資料填寫詳細資訊。</p>
                <?php endif; ?>
                <a href="<?= BASE_URL ?>/logs/daily" class="btn btn-primary w-100 mb-2">今日填寫</a>
                <a href="<?= BASE_URL ?>/chemo" class="btn btn-outline-secondary w-100">化療紀錄</a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="col-md-9">
        <!-- Alerts -->
        <?php foreach ($alerts as $alert): ?>
            <div class="alert alert-<?php echo $alert['type']; ?> alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> <?php echo $alert['msg']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endforeach; ?>

        <!-- Key Stats (Optional, maybe Combined Charts are better) -->

        <!-- Charts Row 1 -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">體重與體溫趨勢 (近30天)</div>
                    <div class="card-body">
                        <canvas id="vitalChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">消化道症狀 (腹瀉/噁心)</div>
                    <div class="card-body">
                        <canvas id="giChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row 2 -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">神經毒性與疲勞 (手腳麻木監測)</div>
                    <div class="card-body">
                        <canvas id="neuroChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const dates = <?php echo json_encode($chartData['dates']); ?>;
const weightData = <?php echo json_encode($chartData['weight']); ?>;
const tempData = <?php echo json_encode($chartData['temp']); ?>;
const diarrheaData = <?php echo json_encode($chartData['diarrhea']); ?>;
const nauseaData = <?php echo json_encode($chartData['nausea']); ?>;
const fatigueData = <?php echo json_encode($chartData['fatigue']); ?>;
const numbnessData = <?php echo json_encode($chartData['numbness']); ?>;

// Vital Chart
new Chart(document.getElementById('vitalChart'), {
    type: 'line',
    data: {
        labels: dates,
        datasets: [{
            label: '體重 (kg)',
            data: weightData,
            borderColor: 'rgb(75, 192, 192)',
            yAxisID: 'y'
        }, {
            label: '體溫 (°C)',
            data: tempData,
            borderColor: 'rgb(255, 99, 132)',
            yAxisID: 'y1'
        }]
    },
    options: {
        interaction: { mode: 'index', intersect: false },
        scales: {
            y: { type: 'linear', display: true, position: 'left', title: {display: true, text: 'kg'} },
            y1: { type: 'linear', display: true, position: 'right', grid: { drawOnChartArea: false }, title: {display: true, text: '°C'} }
        }
    }
});

// GI Chart
new Chart(document.getElementById('giChart'), {
    type: 'bar',
    data: {
        labels: dates,
        datasets: [{
            label: '腹瀉次數',
            data: diarrheaData,
            backgroundColor: 'rgba(255, 206, 86, 0.5)',
            borderColor: 'rgba(255, 206, 86, 1)',
            borderWidth: 1,
            type: 'bar',
            yAxisID: 'y'
        }, {
            label: '噁心程度 (0-10)',
            data: nauseaData,
            borderColor: 'rgb(54, 162, 235)',
            type: 'line',
            yAxisID: 'y1'
        }]
    },
    options: {
        scales: {
            y: { beginAtZero: true, title: {display: true, text: '次數'} },
            y1: { beginAtZero: true, max: 10, position: 'right', grid: { drawOnChartArea: false }, title: {display: true, text: '評分'} }
        }
    }
});

// Neuro Chart
new Chart(document.getElementById('neuroChart'), {
    type: 'line',
    data: {
        labels: dates,
        datasets: [{
            label: '手腳麻木 (0-10)',
            data: numbnessData,
            borderColor: 'rgb(153, 102, 255)',
            backgroundColor: 'rgba(153, 102, 255, 0.2)',
            fill: true
        }, {
            label: '疲勞程度 (0-10)',
            data: fatigueData,
            borderColor: 'rgb(201, 203, 207)',
            borderDash: [5, 5]
        }]
    },
    options: {
        scales: {
            y: { beginAtZero: true, max: 10, title: {display: true, text: '評分'} }
        }
    }
});
</script>
