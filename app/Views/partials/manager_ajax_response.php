<table id="ajaxManagerRows">
    <?= view('partials/manager_table_rows', ['requests'=>$requests]) ?>
</table>

<div id="ajaxManagerPagination">
   <?= view('partials/pagination', ['pager'=>$pager]) ?>
</div>
