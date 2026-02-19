<table id="ajaxAdminRows">
    <?= view('partials/admin_table_rows', ['requests'=>$requests]) ?>
</table>

<div id="ajaxAdminPagination">
    <?= view('partials/pagination', ['pager'=>$pager]) ?>
</div>
