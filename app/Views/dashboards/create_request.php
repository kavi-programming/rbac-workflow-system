<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h3 class="mb-5 mt-5">Create New Request</h3>

<div class="card p-4">
    <form method="post" action="<?= base_url('request/create') ?>">
        <?= csrf_field() ?>
        <div class="mb-3">
            <input type="text" name="title" class="form-control" placeholder="Title" value="<?= old('title') ?>">
            <?php if(session('errors.title')): ?>
                <div class="text-danger small mt-1">
                    <?= session('errors.title') ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <textarea name="description" class="form-control" rows="4" placeholder="Description"><?= old('description') ?></textarea>
            <?php if(session('errors.description')): ?>
                <div class="text-danger small mt-1">
                    <?= session('errors.description') ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <select name="category" class="form-select"> 
                <option value="" disabled selected>Select Category</option>
                <option value="Leave" <?= old('category')=='Leave'?'selected':'' ?>>Leave</option>
                <option value="Purchase" <?= old('category')=='Purchase'?'selected':'' ?>>Purchase</option>
                <option value="IT Support" <?= old('category')=='IT Support'?'selected':'' ?>>IT Support</option>
                <option value="Travel" <?= old('category')=='Travel'?'selected':'' ?>>Travel</option>
            </select>
            <?php if(session('errors.category')): ?>
                <div class="text-danger small mt-1">
                    <?= session('errors.category') ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <select name="priority" class="form-select">
                <option value="" disabled selected>Select Priority</option>
                <option value="Low" <?= old('priority')=='Low'?'selected':'' ?>>Low</option>
                <option value="Medium" <?= old('priority')=='Medium'?'selected':'' ?>>Medium</option>
                <option value="High" <?= old('priority')=='High'?'selected':'' ?>>High</option>
            </select>
            <?php if(session('errors.priority')): ?>
                <div class="text-danger small mt-1">
                    <?= session('errors.priority') ?>
                </div>
            <?php endif; ?>
        </div>
        <button class="btn btn-gradient">Submit Request</button>
    </form>
</div>

<?= $this->endSection() ?>
