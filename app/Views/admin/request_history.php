<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4 mt-4">
    <h4 class="mb-0">Request History</h4>

    <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary">
        ← Back to Dashboard
    </a>
</div>

<table class="table table-bordered shadow-sm">
    <thead class="table-light">
        <tr>
            <th class="ps-3">Old Status</th>
            <th>New Status</th>
            <th>Changed By</th>
            <th>Timestamp</th>
        </tr>
    </thead>
    <tbody>
        <?php if(empty($logs)): ?>
            <tr>
                <td colspan="5" class="text-center text-muted">
                    No history found
                </td>
            </tr>
        <?php endif; ?>

        <?php foreach($logs as $log): ?>
        <tr>
            <td class="ps-3"><?= esc($log['old_status']) ?></td>
            <td><?= esc($log['new_status']) ?></td>
            <td><?= ucfirst(esc($log['role'])) ?></td>
            <td><?= esc($log['created_at']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $this->endSection() ?>
