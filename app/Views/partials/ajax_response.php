<table id="ajaxTableRows">
    <?= view('partials/request_table_rows', ['requests'=>$requests]) ?>
</table>

<div id="ajaxPagination">
    <?= view('partials/pagination', ['pager'=>$pager]) ?>
</div>
