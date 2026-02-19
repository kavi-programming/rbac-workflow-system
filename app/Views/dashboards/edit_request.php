<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h3 class="mb-5 mt-5">Edit Request</h3>

<form method="post" action="<?= base_url('request/resubmit/'.$request['id']) ?>">

    <div class="mb-3">
        <label class="mb-2">Title</label>
        <input type="text" name="title" class="form-control" value="<?= esc($request['title']) ?>">
        <?php if(session('errors.title')): ?>
            <div class="text-danger small mt-1">
                <?= session('errors.title') ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label class="mb-2">Description</label>
        <textarea name="description" class="form-control" rows="4"><?= esc($request['description']) ?></textarea>
        <?php if(session('errors.description')): ?>
            <div class="text-danger small mt-1">
                <?= session('errors.description') ?>
            </div>
        <?php endif; ?>
    </div>

    <button type="submit" class="btn btn-success">
        Submit
    </button>
</form>

<?= $this->endSection() ?>
