<?php if (isset($pager)): ?>
    <div class="d-flex justify-content-center mt-4" id="ajaxPagination">
        <?= $pager->links('default','default_full') ?>
    </div>
<?php endif; ?>
