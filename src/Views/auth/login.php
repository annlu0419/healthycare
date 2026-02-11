<div class="row justify-content-center mt-5">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h3 class="text-center mb-4"><i class="fas fa-user-circle text-primary"></i> 病患登入</h3>
                
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST" action="<?= BASE_URL ?>/login">
                    <div class="mb-3">
                        <label for="username" class="form-label">帳號</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">密碼</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-block">登入</button>
                    </div>
                </form>
                <div class="mt-3 text-center text-muted">
                    <small>大腸癌化療病患自我追蹤系統</small>
                    <br>
                    <small><a href="<?= BASE_URL ?>/register?u=patient&p=123456">快速註冊(測試用)</a></small>
                </div>
            </div>
        </div>
    </div>
</div>
