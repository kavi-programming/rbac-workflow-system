<div class="col-md-2 sidebar">

    <h5 class="text-center mb-4">RBAC System</h5>

    <p class="text-center small">
        Logged in as <br>
        <strong><?= ucfirst(session()->get('role')) ?></strong>
    </p>

    <hr style="border-color: rgba(255,255,255,0.3);">

    <a href="<?= base_url('dashboard') ?>">Dashboard</a>

    <?php if(session()->get('role') == 'user'): ?>
        <a href="<?= base_url('request/new') ?>">New Request</a>
    <?php endif; ?>

    <a href="<?= base_url('logout') ?>" class="mt-3">Logout</a>

</div>
