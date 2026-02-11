<div class="row">
    <div class="col-md-12 mb-4">
        <h2>化療週期管理</h2>
        <button class="btn btn-success" type="button" data-bs-toggle="collapse" data-bs-target="#addCycleForm">
            <i class="fas fa-plus"></i> 新增週期
        </button>
    </div>

    <!-- Add Form -->
    <div class="col-md-12 collapse mb-4" id="addCycleForm">
        <div class="card card-body">
            <h5 class="card-title">新增化療紀錄</h5>
            <form action="<?= BASE_URL ?>/chemo/store" method="POST">
                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label class="form-label">週期數</label>
                        <input type="number" class="form-control" name="cycle_no" required placeholder="1">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">施打日期</label>
                        <input type="date" class="form-control" name="infusion_date" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">方案</label>
                        <input type="text" class="form-control" name="regimen" placeholder="FOLFOX">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">劑量調整備註</label>
                    <input type="text" class="form-control" name="dose_notes" placeholder="如：因白血球低減量 20%">
                </div>
                <div class="mb-3">
                    <label class="form-label">其他備註</label>
                    <textarea class="form-control" name="notes" rows="2"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">新增</button>
            </form>
        </div>
    </div>

    <!-- List -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>週期</th>
                                <th>日期</th>
                                <th>方案</th>
                                <th>劑量備註</th>
                                <th>筆記</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($cycles)): ?>
                                <tr><td colspan="5" class="text-center">尚未有紀錄</td></tr>
                                <tr><td colspan="6" class="text-center">尚未有紀錄</td></tr>
                            <?php else: ?>
                                <?php foreach ($cycles as $c): ?>
                                <tr>
                                    <td><span class="badge bg-info text-dark">#<?php echo $c['cycle_no']; ?></span></td>
                                    <td><?php echo $c['infusion_date']; ?></td>
                                    <td><?php echo htmlspecialchars($c['regimen'] ?? ''); ?></td>
                                    <td><?php echo $c['dose_notes']; ?></td>
                                    <td><small><?php echo htmlspecialchars($c['notes'] ?? ''); ?></small></td>
                                    <td>
                                        <button class="btn btn-sm btn-info text-white" onclick='editChemo(<?= htmlspecialchars(json_encode($c), ENT_QUOTES, 'UTF-8') ?>)'>
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="<?= BASE_URL ?>/chemo/delete" method="POST" class="d-inline" onsubmit="return confirm('確定刪除?');">
                                            <input type="hidden" name="id" value="<?= $c['id'] ?>">
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

<script>
function editChemo(data) {
    document.querySelector('#addCycleForm').classList.add('show');
    const form = document.querySelector('#addCycleForm form');
    
    // Add hidden ID field if not exists
    if(!form.querySelector('input[name="id"]')) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'id';
        form.appendChild(input);
    }
    
    form.querySelector('input[name="id"]').value = data.id;
    form.querySelector('input[name="cycle_no"]').value = data.cycle_no;
    form.querySelector('input[name="infusion_date"]').value = data.infusion_date;
    form.querySelector('input[name="regimen"]').value = data.regimen;
    form.querySelector('input[name="dose_notes"]').value = data.dose_notes;
    form.querySelector('textarea[name="notes"]').value = data.notes; // Changed to textarea
    
    // Change Title
    document.querySelector('#addCycleForm .card-title').innerText = '編輯化療紀錄';
    window.scrollTo(0, 0);
}
</script>
