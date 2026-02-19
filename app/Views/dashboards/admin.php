<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h3 class="mb-5 mt-5">Admin Control Panel</h3>
<div class="row mb-4 align-items-end">

    <div class="col-md-2">
        <label class="form-label fw-semibold">Role</label>
        <select id="roleFilter" class="form-select" onchange="filterAdmin()">
            <option value="">-- Select Role --</option>
            <option value="user">User</option>
            <option value="manager">Manager</option>
            <option value="admin">Admin</option>
        </select>
    </div>
    <div class="col-md-2">
        <label class="form-label fw-semibold">Status</label>
        <select id="adminStatusFilter" class="form-select" onchange="filterAdmin()">
            <option value="">-- Select Status --</option>
            <option value="Submitted">Submitted</option>
            <option value="Approved">Approved</option>
            <option value="Rejected">Rejected</option>
            <option value="Needs Clarification">Needs Clarification</option>
            <option value="Closed">Closed</option>
            <option value="Reopened">Reopened</option>
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Date Range</label>
        <div class="d-flex gap-2">
            <input type="date" id="dateFrom" class="form-control" onchange="filterAdmin()">
            <span class="align-self-center">to</span>
            <input type="date" id="dateTo" class="form-control" onchange="filterAdmin()">
        </div>
    </div>
    <div class="col-md-2">
        <label class="form-label invisible">Reset</label>
        <button class="btn btn-outline-secondary w-100" onclick="resetAdminFilters()">
            Reset
        </button>
    </div>
</div>
<div id="adminContainer">
    <div class="card shadow-sm">
        <div class="card-body">

            <h6 class="mb-3">All Requests</h6>

            <table class="table table-bordered table-hover bg-white">
                <thead class="table-light">
                    <tr>
                        <th class="ps-5">Title</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Updated</th>
                        <th width="150">Action</th>
                    </tr>
                </thead>

                <tbody id="adminTableBody">
                    <?= view('partials/admin_table_rows', ['requests'=>$requests]) ?>
                </tbody>

            </table>
        </div>
    </div>

    <div id="paginationContainer">
        <?= view('partials/pagination', ['pager'=>$pager]) ?>
    </div>
</div>
<script>
    function loadAdminData(url) {
        fetch(url, {
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            }
        })
        .then(response => response.text())
        .then(data => {
            const parser    = new DOMParser();
            const doc       = parser.parseFromString(data, "text/html");
            const rows      = doc.querySelector("#ajaxAdminRows");
            const pagination = doc.querySelector("#ajaxAdminPagination");

            if (!rows || !pagination) {
                console.error("Admin AJAX response incorrect");
                return;
            }

            document.getElementById("adminTableBody").innerHTML = rows.innerHTML;
            document.getElementById("paginationContainer").innerHTML = pagination.innerHTML;
        })
        .catch(error => console.error("Load error:", error));
    }


    function filterAdmin() {

        let role      = document.getElementById('roleFilter').value;
        let status    = document.getElementById('adminStatusFilter').value;
        let startDate = document.getElementById('dateFrom').value;
        let endDate   = document.getElementById('dateTo').value;

        let url = "<?= base_url('admin/ajax-filter') ?>" + "?role=" + encodeURIComponent(role) + "&status=" + encodeURIComponent(status) + "&start=" + encodeURIComponent(startDate) + "&end=" + encodeURIComponent(endDate);
        loadAdminData(url);
    }

    document.addEventListener("click", function(e) {
        if (e.target.closest(".pagination a")) {
            e.preventDefault();
            let url = e.target.closest(".pagination a").getAttribute("href");

            loadAdminData(url);
        }
    });

    document.addEventListener('click', function(e){
        if(e.target.classList.contains('actionBtn')){
            const button = e.target;
            const id = button.dataset.id;
            const action = button.dataset.action;
            const row = button.closest('tr');

            fetch("<?= base_url('admin/action') ?>", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: JSON.stringify({
                    request_id: id,
                    action: action
                })
            })
            .then(res => res.json())
            .then(data => {

                if(data.status === 'success'){
                    const badge = row.querySelector('.statusBadge');
                    badge.innerText = action;
                    let colorMap = {
                        'Submitted':'primary',
                        'Approved':'success',
                        'Rejected':'danger',
                        'Needs Clarification':'warning',
                        'Closed':'dark',
                        'Reopened':'info'
                    };

                    badge.className = "badge bg-" + colorMap[action] + " statusBadge";

                    row.querySelector('.actionCell').innerHTML = '';
                    filterAdmin();
                } else {
                    alert("Something went wrong!");
                }

            });
        }
    });
    function resetAdminFilters() {

        document.getElementById('roleFilter').value = "";
        document.getElementById('adminStatusFilter').value = "";
        document.getElementById('dateFrom').value = "";
        document.getElementById('dateTo').value = "";

        let url = "<?= base_url('admin/ajax-filter') ?>";
        loadAdminData(url);
    }
</script>
<?= $this->endSection() ?>
