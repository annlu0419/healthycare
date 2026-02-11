<div class="row">
    <div class="col-md-12">
        <h2 class="mb-4">匯出報告</h2>
        
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-file-pdf fa-4x text-danger mb-3"></i>
                        <h5 class="card-title">回診摘要報告 (PDF/列印)</h5>
                        <p class="card-text">包含近期的體徵趨勢、警示與摘要，適合提供給醫師。</p>
                        <hr>
                        <form action="<?= BASE_URL ?>/export" method="GET" target="_blank">
                            <input type="hidden" name="type" value="print">
                            <div class="mb-3">
                                <label class="form-label">選擇區間</label>
                                <select class="form-select" name="days">
                                    <option value="7">近 7 天</option>
                                    <option value="14" selected>近 14 天</option>
                                    <option value="30">近 30 天</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-danger w-100">生成報告</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-file-csv fa-4x text-success mb-3"></i>
                        <h5 class="card-title">原始資料匯出 (Excel/CSV)</h5>
                        <p class="card-text">下載所有每日追蹤的原始數據，可自行分析。</p>
                        <hr>
                        <div class="mt-4 pt-3">
                            <a href="<?= BASE_URL ?>/export?type=csv" class="btn btn-success w-100">下載 CSV 檔案</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
