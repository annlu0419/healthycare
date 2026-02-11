<div class="row justify-content-center">
    <div class="col-md-12">
        <h2 class="mb-4">個人基本資料</h2>
        <div class="card">
            <div class="card-header bg-white">
                基本診斷資料
            </div>
            <div class="card-body">
                <form action="<?= BASE_URL ?>/profile/update" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">癌症分期 (Stage)</label>
                            <select class="form-select" name="cancer_stage">
                                <option value="">請選擇</option>
                                <option value="I" <?php echo ($profile['cancer_stage'] ?? '') == 'I' ? 'selected' : ''; ?>>I 期</option>
                                <option value="II" <?php echo ($profile['cancer_stage'] ?? '') == 'II' ? 'selected' : ''; ?>>II 期</option>
                                <option value="III" <?php echo ($profile['cancer_stage'] ?? '') == 'III' ? 'selected' : ''; ?>>III 期</option>
                                <option value="IV" <?php echo ($profile['cancer_stage'] ?? '') == 'IV' ? 'selected' : ''; ?>>IV 期</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">TNM 分期</label>
                            <input type="text" class="form-control" name="tnm_stage" value="<?php echo htmlspecialchars($profile['tnm_stage'] ?? ''); ?>" placeholder="例如: T3N1M0">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">生物標記 (Biomarkers)</label>
                        <div class="d-flex gap-3">
                            <?php 
                                $bio = explode(',', $profile['biomarkers'] ?? '');
                            ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="biomarkers[]" value="KRAS" id="kras" <?php echo in_array('KRAS', $bio) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="kras">KRAS 突變</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="biomarkers[]" value="BRAF" id="braf" <?php echo in_array('BRAF', $bio) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="braf">BRAF 突變</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="biomarkers[]" value="MSI-H" id="msi" <?php echo in_array('MSI-H', $bio) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="msi">MSI-H</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">目前化療方案</label>
                            <input type="text" class="form-control" name="regimen_name" value="<?php echo htmlspecialchars($profile['regimen_name'] ?? ''); ?>" placeholder="例如: FOLFOX, CAPOX">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">手術日期</label>
                            <input type="date" class="form-control" name="surgery_date" value="<?php echo $profile['surgery_date'] ?? ''; ?>">
                        </div>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" name="has_stoma" id="stoma" <?php echo ($profile['has_stoma'] ?? 0) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="stoma">是否有造口 (人工肛門)</label>
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> 儲存資料</button>
                    <a href="<?= BASE_URL ?>/dashboard" class="btn btn-secondary">返回總覽</a>
                </form>
            </div>
        </div>
    </div>
</div>
