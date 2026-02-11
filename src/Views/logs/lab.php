<div class="row">
    <div class="col-md-12 mb-3">
        <h2>檢驗報告 (Lab Data)</h2>
        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#addLabForm">
            <i class="fas fa-plus"></i> 新增檢驗數據
        </button>
    </div>

    <!-- Add Form -->
    <div class="col-md-12 collapse mb-4" id="addLabForm">
        <div class="card card-body">
            <h5 class="card-title">輸入血液/生化檢查數值</h5>
            <form action="<?= BASE_URL ?>/lab/store" method="POST">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">檢查日期</label>
                        <input type="date" class="form-control" name="test_date" required value="<?php echo date('Y-m-d'); ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">白血球 (WBC)</label>
                        <input type="number" step="0.01" class="form-control" name="wbc">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label text-danger">嗜中性球 (ANC)</label>
                        <input type="number" step="0.01" class="form-control" name="anc" placeholder="重要!">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">血紅素 (Hb)</label>
                        <input type="number" step="0.1" class="form-control" name="hb">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">血小板 (Plt)</label>
                        <input type="number" step="1" class="form-control" name="platelet">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">肝指數 (AST/GOT)</label>
                        <input type="number" step="1" class="form-control" name="ast">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">肝指數 (ALT/GPT)</label>
                        <input type="number" step="1" class="form-control" name="alt">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">肌酸酐 (Cr)</label>
                        <input type="number" step="0.01" class="form-control" name="creatinine">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">腫瘤指數 (CEA)</label>
                        <input type="number" step="0.01" class="form-control" name="cea">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">備註</label>
                    <input type="text" class="form-control" name="notes">
                </div>
                <button type="submit" class="btn btn-primary">儲存</button>
            </form>
        </div>
    </div>

    <!-- List -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr class="table-secondary">
                                <th>日期</th>
                                <th>WBC</th>
                                <th>ANC</th>
                                <th>Hb</th>
                                <th>Plt</th>
                                <th>CEA</th>
                                <th>備註</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($results)): ?>
                                <tr><td colspan="7" class="text-center">尚未有紀錄</td></tr>
                            <?php else: ?>
                                <?php foreach ($results as $r): ?>
                                <tr>
                                    <td><?php echo $r['test_date']; ?></td>
                                    <td><?php echo $r['wbc']; ?></td>
                                    <td class="<?php echo ($r['anc'] > 0 && $r['anc'] < 1500) ? 'text-danger fw-bold' : ''; ?>">
                                        <?php echo $r['anc']; ?>
                                    </td>
                                    <td><?php echo $r['hb']; ?></td>
                                    <td><?php echo $r['platelet']; ?></td>
                                    <td><?php echo $r['cea']; ?></td>
                                    <td><small><?php echo htmlspecialchars($r['notes'] ?? ''); ?></small></td>
                                    <td>
                                        <button class="btn btn-sm btn-info text-white" onclick='editLab(<?= htmlspecialchars(json_encode($r), ENT_QUOTES, 'UTF-8') ?>)'>
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="<?= BASE_URL ?>/lab/delete" method="POST" class="d-inline" onsubmit="return confirm('確定刪除?');">
                                            <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="mt-2 text-muted small">
                    <i class="fas fa-info-circle"></i> ANC < 1500 為警示值 (依化療方案不同標準可能不同)
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function editLab(data) {
    document.querySelector('#addLabForm').classList.add('show');
    const form = document.querySelector('#addLabForm form');
    
    // Add hidden ID field if not exists
    if(!form.querySelector('input[name="id"]')) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'id';
        form.appendChild(input);
    }
    
    form.querySelector('input[name="id"]').value = data.id;
    form.querySelector('input[name="test_date"]').value = data.test_date;
    form.querySelector('input[name="wbc"]').value = data.wbc;
    form.querySelector('input[name="anc"]').value = data.anc;
    form.querySelector('input[name="hb"]').value = data.hb;
    form.querySelector('input[name="platelet"]').value = data.platelet;
    form.querySelector('input[name="ast"]').value = data.ast;
    form.querySelector('input[name="alt"]').value = data.alt;
    form.querySelector('input[name="creatinine"]').value = data.creatinine;
    form.querySelector('input[name="cea"]').value = data.cea;
    form.querySelector('input[name="notes"]').value = data.notes;
    
    // Change Title
    document.querySelector('#addLabForm .card-title').innerText = '編輯檢驗數據';
    window.scrollTo(0, 0);
}
</script>
