<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h3 class="mb-5 mt-5">Manager Approval Panel</h3>
<div class="row mb-4 align-items-end">
    <div class="col-md-3">
        <select id="categoryFilter" class="form-select" onchange="filterManager()">
            <option value="">-- Select Category --</option>
            <option value="Leave">Leave</option>
            <option value="Purchase">Purchase</option>
            <option value="IT Support">IT Support</option>
            <option value="Travel">Travel</option>
        </select>
    </div>
    <div class="col-md-3">
        <select id="priorityFilter" class="form-select" onchange="filterManager()">
            <option value="">-- Select Priorities --</option>
            <option value="Low">Low</option>
            <option value="Medium">Medium</option>
            <option value="High">High</option>
        </select>
    </div>
    <div class="col-md-3">
        <select id="statusFilter" class="form-select" onchange="filterManager()">
            <option value="">-- Select Status --</option>
            <option value="Submitted">Submitted</option>
            <option value="Approved">Approved</option>
            <option value="Rejected">Rejected</option>
            <option value="Needs Clarification">Needs Clarification</option>
            <option value="Closed">Closed</option>
            <option value="Reopened">Reopened</option>
        </select>
    </div>
    <div class="col-md-1">
        <button class="btn btn-outline-secondary w-100" onclick="resetFilter()">
            Reset
        </button>
    </div>
</div>
<div id="managerContainer">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h6 class="mb-3">Requests Pending Approval (Submitted)</h6>

            <table class="table table-hover bg-white">
                <thead class="table-light">
                    <tr>
                        <th class="ps-5">Title</th>
                        <th>Category</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th width="220">Action</th>
                    </tr>
                </thead>
                <tbody id="managerTableBody">
                    <?= view('partials/manager_table_rows', ['requests'=>$requests]) ?>
                </tbody>

            </table>
        </div>
    </div>
    <div id="managerPagination">
        <?= view('partials/pagination', ['pager'=>$pager]) ?>
    </div>
</div>
<script>
    function loadManagerData(url) {
        fetch(url, {
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            }
        })
        .then(response => response.text())
        .then(data => {

            const parser        = new DOMParser();
            const doc           = parser.parseFromString(data, "text/html");
            const rows          = doc.querySelector("#ajaxManagerRows");
            const pagination    = doc.querySelector("#ajaxManagerPagination");

            if (!rows || !pagination) {
                console.error("Manager AJAX response incorrect");
                return;
            }

            document.getElementById("managerTableBody").innerHTML = rows.innerHTML;
            document.getElementById("managerPagination").innerHTML = pagination.innerHTML;
        });
    }


    function filterManager() {
        let category = document.getElementById('categoryFilter').value;
        let priority = document.getElementById('priorityFilter').value;
        let status   = document.getElementById('statusFilter').value;

        let url = "<?= base_url('manager/ajax-filter') ?>" + "?category=" + encodeURIComponent(category) + "&priority=" + encodeURIComponent(priority) + "&status=" + encodeURIComponent(status);
        loadManagerData(url);
    }

    document.addEventListener("click", function(e) {
        if (e.target.closest(".pagination a")) {
            e.preventDefault();
            let url = e.target.closest(".pagination a").getAttribute("href");
            loadManagerData(url);
        }

        const btn = e.target.closest(".actionBtn");
        if (!btn) return;

        e.preventDefault();

        let requestId = btn.dataset.id;
        let action    = btn.dataset.action;

        fetch("<?= base_url('manager/action') ?>", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest"
            },
            body: JSON.stringify({
                request_id: requestId,
                action: action
            })
        })
        .then(res => res.json())
        .then(data => {

            if (data.status === "success") {
                let row     = btn.closest("tr");
                let badge   = row.querySelector(".statusBadge");
                badge.textContent = action;
                badge.className = "badge bg-" + data.color + " statusBadge";
                filterManager();
            }
        })
        .catch(err => console.error(err));
    });
    function resetFilter() {
        document.getElementById('categoryFilter').value = "";
        document.getElementById('priorityFilter').value = "";
        document.getElementById('statusFilter').value   = "";

        filterManager();
    }
</script>
<?= $this->endSection() ?>

