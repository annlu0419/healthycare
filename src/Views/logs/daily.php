<?php
$d = $data['daily'] ?? [];
$dt = $data['diet'] ?? [];
$exercise = $data['exercise'] ?? [];
?>

<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-calendar-day"></i> 每日記錄</h2>
            <form action="<?= BASE_URL ?>/logs/daily" method="GET" class="d-flex">
                <input type="date" name="date" class="form-control me-2" value="<?php echo $date; ?>" onchange="this.form.submit()">
                <button type="submit" class="btn btn-outline-primary">前往</button>
            </form>
        </div>

        <?php if(isset($_GET['saved'])): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> 資料已儲存！</div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/logs/save" method="POST">
            <input type="hidden" name="log_date" value="<?php echo $date; ?>">

            <!-- Vitals & Symptoms -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-procedures"></i> 生理與症狀
                </div>
                <div class="card-body">
                    <h5 class="text-muted mb-3 border-bottom pb-2">基本體徵</h5>
                    <div class="row mb-3">
                        <div class="col-6 col-md-3">
                            <label class="form-label">體重 (kg)</label>
                            <input type="number" step="0.1" class="form-control" name="weight" value="<?php echo $d['weight'] ?? ''; ?>">
                        </div>
                        <div class="col-6 col-md-3">
                            <label class="form-label">體溫 (°C)</label>
                            <input type="number" step="0.1" class="form-control" name="temperature" value="<?php echo $d['temperature'] ?? ''; ?>">
                            <?php if(($d['temperature'] ?? 0) >= 38): ?>
                                <small class="text-danger fw-bold"><i class="fas fa-exclamation-triangle"></i> 發燒警示！請密切觀察</small>
                            <?php endif; ?>
                        </div>
                        <div class="col-6 col-md-3">
                            <label class="form-label">血壓 (收縮/舒張)</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="bp_systolic" placeholder="120" value="<?php echo $d['bp_systolic'] ?? ''; ?>">
                                <span class="input-group-text">/</span>
                                <input type="number" class="form-control" name="bp_diastolic" placeholder="80" value="<?php echo $d['bp_diastolic'] ?? ''; ?>">
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <label class="form-label">脈搏 (bpm)</label>
                            <input type="number" class="form-control" name="pulse" value="<?php echo $d['pulse'] ?? ''; ?>">
                        </div>
                    </div>

                    <h5 class="text-muted mb-3 border-bottom pb-2">消化道症狀</h5>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label">噁心 (0-10)</label>
                            <input type="range" class="form-range" min="0" max="10" name="nausea_score" value="<?php echo $d['nausea_score'] ?? 0; ?>" oninput="this.nextElementSibling.value = this.value">
                            <output><?php echo $d['nausea_score'] ?? 0; ?></output>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">嘔吐次數</label>
                            <input type="number" class="form-control" name="vomit_count" value="<?php echo $d['vomit_count'] ?? 0; ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">腹瀉次數</label>
                            <input type="number" class="form-control" name="diarrhea_count" value="<?php echo $d['diarrhea_count'] ?? 0; ?>">
                        </div>
                        <div class="col-md-3">
                             <label class="form-label">便祕</label>
                             <div class="form-check form-switch pt-2">
                                <input class="form-check-input" type="checkbox" name="is_constipated" <?php echo ($d['is_constipated'] ?? 0) ? 'checked' : ''; ?>>
                                <label class="form-check-label">是</label>
                             </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">糞便型態 (Bristol 1-7)</label>
                            <select class="form-select" name="stool_type">
                                <option value="">請選擇</option>
                                <option value="1" <?php echo ($d['stool_type'] ?? '') == 1 ? 'selected' : ''; ?>>1. 硬球狀 (便秘)</option>
                                <option value="2" <?php echo ($d['stool_type'] ?? '') == 2 ? 'selected' : ''; ?>>2. 表面凹凸長條 (輕微便秘)</option>
                                <option value="3" <?php echo ($d['stool_type'] ?? '') == 3 ? 'selected' : ''; ?>>3. 表面裂痕長條 (正常)</option>
                                <option value="4" <?php echo ($d['stool_type'] ?? '') == 4 ? 'selected' : ''; ?>>4. 表面光滑長條 (正常)</option>
                                <option value="5" <?php echo ($d['stool_type'] ?? '') == 5 ? 'selected' : ''; ?>>5. 柔軟塊狀 (輕微腹瀉)</option>
                                <option value="6" <?php echo ($d['stool_type'] ?? '') == 6 ? 'selected' : ''; ?>>6. 鬆散糊狀 (腹瀉)</option>
                                <option value="7" <?php echo ($d['stool_type'] ?? '') == 7 ? 'selected' : ''; ?>>7. 水狀 (嚴重腹瀉)</option>
                            </select>
                        </div>
                        <div class="col-md-6 pt-4">
                            <div class="form-check text-danger">
                                <input class="form-check-input" type="checkbox" name="has_blood_stool" <?php echo ($d['has_blood_stool'] ?? 0) ? 'checked' : ''; ?>>
                                <label class="form-check-label fw-bold">是否有血便</label>
                            </div>
                        </div>
                    </div>

                    <h5 class="text-muted mb-3 border-bottom pb-2">其他症狀評分 (0-10)</h5>
                    <div class="row mb-3">
                        <div class="col-6 col-md-3 mb-3">
                            <label>疼痛</label>
                            <input type="number" class="form-control" name="pain_score" min="0" max="10" value="<?php echo $d['pain_score'] ?? 0; ?>">
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <label>腹脹</label>
                            <input type="number" class="form-control" name="bloating_score" min="0" max="10" value="<?php echo $d['bloating_score'] ?? 0; ?>">
                        </div>
                         <div class="col-6 col-md-3 mb-3">
                            <label>手腳麻木</label>
                            <input type="number" class="form-control" name="numbness_score" min="0" max="10" value="<?php echo $d['numbness_score'] ?? 0; ?>">
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <label>疲勞</label>
                            <input type="number" class="form-control" name="fatigue_score" min="0" max="10" value="<?php echo $d['fatigue_score'] ?? 0; ?>">
                        </div>
                         <div class="col-6 col-md-3 mb-3">
                            <label>食慾</label>
                            <input type="number" class="form-control" name="appetite_score" min="0" max="10" value="<?php echo $d['appetite_score'] ?? 0; ?>">
                        </div>
                         <div class="col-6 col-md-3 mb-3">
                            <label>口腔潰瘍</label>
                            <input type="number" class="form-control" name="mouth_sore_score" min="0" max="10" value="<?php echo $d['mouth_sore_score'] ?? 0; ?>">
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <label>睡眠品質</label>
                            <input type="number" class="form-control" name="sleep_score" min="0" max="10" value="<?php echo $d['sleep_score'] ?? 0; ?>">
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <label>情緒壓力</label>
                            <input type="number" class="form-control" name="mood_score" min="0" max="10" value="<?php echo $d['mood_score'] ?? 0; ?>">
                        </div>
                    </div>
                    <div class="mb-2">
                        <label>今日備註</label>
                        <textarea class="form-control" name="daily_notes" rows="2"><?php echo htmlspecialchars($d['notes'] ?? ''); ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Diet -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-apple-alt"></i> 飲食紀錄
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">水分攝取 (ml)</label>
                            <input type="number" class="form-control" name="water_intake" value="<?php echo $dt['water_intake'] ?? 0; ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">進食餐次</label>
                            <input type="number" class="form-control" name="meal_count" value="<?php echo $dt['meal_count'] ?? 3; ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">風險食物攝取</label>
                        <div class="d-flex gap-3 flex-wrap">
                            <?php 
                                $risks = explode(',', $dt['risk_foods'] ?? '');
                            ?>
                             <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="risk_foods[]" value="dairy" id="dairy" <?php echo in_array('dairy', $risks) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="dairy">乳製品</label>
                            </div>
                             <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="risk_foods[]" value="fried" id="fried" <?php echo in_array('fried', $risks) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="fried">油炸</label>
                            </div>
                             <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="risk_foods[]" value="spicy" id="spicy" <?php echo in_array('spicy', $risks) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="spicy">辛辣</label>
                            </div>
                             <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="risk_foods[]" value="raw" id="raw" <?php echo in_array('raw', $risks) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="raw">生冷食物</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label>簡易飲食紀錄</label>
                        <textarea class="form-control" name="diet_content" rows="2"><?php echo htmlspecialchars($dt['content_summary'] ?? ''); ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Exercise -->
            <div class="card mb-4">
                <div class="card-header bg-info text-dark">
                    <i class="fas fa-running"></i> 運動紀錄
                </div>
                <div class="card-body">
                    <div class="alert alert-light border">
                         <?php if(empty($exercise)): ?>
                            <small class="text-muted">今日尚無運動紀錄。</small>
                        <?php else: ?>
                            <h6>今日已記錄：</h6>
                            <ul class="mb-2">
                                <?php foreach($exercise as $ex): ?>
                                    <li><?php echo htmlspecialchars($ex['exercise_type']); ?> - <?php echo $ex['duration_minutes']; ?> 分鐘 (<?php echo $ex['intensity']; ?>)</li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>

                    <h6>新增運動 (需填寫才儲存)</h6>
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label>類型</label>
                            <input type="text" class="form-control" name="exercise_type" placeholder="如: 散步, 瑜伽">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label>時間 (分鐘)</label>
                            <input type="number" class="form-control" name="duration_minutes" placeholder="30">
                        </div>
                        <div class="col-md-3 mb-2">
                             <label>強度</label>
                             <select class="form-select" name="intensity">
                                 <option value="Low">輕度 (如散步)</option>
                                 <option value="Medium">中度 (微喘)</option>
                                 <option value="High">高強度</option>
                             </select>
                        </div>
                         <div class="col-md-12 mb-2">
                             <label>備註</label>
                             <input type="text" class="form-control" name="exercise_notes">
                         </div>
                    </div>
                </div>
            </div>

            <div class="d-grid mb-5">
                <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save"></i> 儲存所有紀錄</button>
            </div>
        </form>
    </div>
</div>
