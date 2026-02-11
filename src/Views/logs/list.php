<div class="row">
    <div class="col-md-12 mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <h2><i class="fas fa-history"></i> 每日紀錄列表</h2>
            <a href="<?= BASE_URL ?>/logs/daily" class="btn btn-primary"><i class="fas fa-plus"></i> 新增今日紀錄</a>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>日期</th>
                                <th>體重</th>
                                <th>體溫</th>
                                <th>不適症狀</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($logs)): ?>
                                <tr><td colspan="5" class="text-center">目前沒有紀錄</td></tr>
                            <?php else: ?>
                                <?php foreach ($logs as $log): ?>
                                <tr>
                                    <td><?php echo $log['log_date']; ?></td>
                                    <td><?php echo $log['weight'] > 0 ? $log['weight'] : '-'; ?></td>
                                    <td class="<?php echo $log['temperature'] >= 38 ? 'text-danger fw-bold' : ''; ?>">
                                        <?php echo $log['temperature'] > 0 ? $log['temperature'] : '-'; ?>
                                    </td>
                                    <td>
                                        <?php 
                                        $symptoms = [];
                                        if ($log['diarrhea_count'] > 0) $symptoms[] = "腹瀉({$log['diarrhea_count']})";
                                        if ($log['vomit_count'] > 0) $symptoms[] = "嘔吐({$log['vomit_count']})";
                                        if ($log['nausea_score'] > 0) $symptoms[] = "噁心";
                                        if ($log['fatigue_score'] >= 5) $symptoms[] = "疲勞";
                                        echo empty($symptoms) ? '<span class="text-muted">-</span>' : implode(', ', $symptoms);
                                        ?>
                                    </td>
                                    <td>
                                        <a href="<?= BASE_URL ?>/logs/daily?date=<?= $log['log_date'] ?>" class="btn btn-sm btn-info text-white">
                                            <i class="fas fa-edit"></i> 編輯
                                        </a>
                                        <form action="<?= BASE_URL ?>/logs/delete" method="POST" class="d-inline" onsubmit="return confirm('確定要刪除 <?= $log['log_date'] ?> 的所有紀錄嗎？(包含飲食運動)');">
                                            <input type="hidden" name="date" value="<?= $log['log_date'] ?>">
                                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
