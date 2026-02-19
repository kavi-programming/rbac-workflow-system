<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h3 class="mb-5 mt-5">My Requests</h3>
<div class="d-flex justify-content-between align-items-center mt-4 mb-3">
    <div class="d-flex gap-2">
        <select id="statusFilter" class="form-select" onchange="filterByStatus()">
            <option value="">-- Select Status --</option>
            <option value="Submitted">Submitted</option>
            <option value="Approved">Approved</option>
            <option value="Rejected">Rejected</option>
            <option value="Needs Clarification">Needs Clarification</option>
            <option value="Closed">Closed</option>
            <option value="Reopened">Reopened</option>
            <option value="No Action">No Action</option>
        </select>
        <button class="btn btn-outline-secondary" onclick="resetFilter()"> Reset</button>
    </div>
</div>
<div id="requestContainer">
    <table class="table table-hover bg-white shadow">
        <thead class="table-light">
            <tr>
                <th class="ps-5">Title</th>
                <th style="width:50%">Description</th>
                <th>Status</th>
                <th style="cursor:pointer;" onclick="sortByDate()">Updated <span id="sortIcon"><i class="bi bi-arrow-down"></i></span></th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody id="tableBody">
            <?= view('partials/request_table_rows', ['requests'=>$requests]) ?>
        </tbody>
    </table>
    
    <div id="paginationContainer">
        <?= view('partials/pagination', ['pager'=>$pager]) ?>
    </div>
</div>
<script>
    let currentOrder = "<?= $current_order ?? 'desc' ?>";

    function loadData(url) {
        fetch(url, {
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            }
        })
        .then(response => response.text())
        .then(data => {
            const parser    = new DOMParser();
            const doc       = parser.parseFromString(data, "text/html");
            const rows      = doc.querySelector("#ajaxTableRows");
            const pagination = doc.querySelector("#ajaxPagination");

            if (!rows || !pagination) {
                console.error("AJAX response format incorrect");
                return;
            }
            document.getElementById('tableBody').innerHTML = rows.innerHTML;
            document.getElementById('paginationContainer').innerHTML = pagination.innerHTML;
        })
        .catch(error => console.error("Load error:", error));
    }


    function sortByDate() {
        currentOrder = (currentOrder === 'desc') ? 'asc' : 'desc';
        let status = document.getElementById('statusFilter').value;
        let url = "<?= base_url('dashboard') ?>?order=" + currentOrder + "&status=" + encodeURIComponent(status);
        loadData(url);
    }

    function filterByStatus() {
        let status = document.getElementById('statusFilter').value;
        let url = "<?= base_url('dashboard') ?>?status=" + encodeURIComponent(status) + "&order=" + currentOrder;
        loadData(url);
    }

    document.addEventListener("click", function(e) {
        if (e.target.closest(".pagination a")) {
            e.preventDefault();
            let url = e.target.closest(".pagination a").getAttribute("href");
            loadData(url);
        }
    });

    function resetFilter() {
        document.getElementById('statusFilter').value = "";
        currentOrder = "desc";
        let url = "<?= base_url('dashboard') ?>?order=" + currentOrder;
        loadData(url);
    }
</script>
<?= $this->endSection() ?>
