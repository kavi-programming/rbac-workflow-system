<!DOCTYPE html>
<html>
    <head>
        <title>RBAC System</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
        <style>
            body {
                background: #f2f2f2;
                font-family: 'Segoe UI', sans-serif;
            }

            /* Sidebar */
            .sidebar {
                min-height: 100vh;
                background: linear-gradient(to bottom, #00c6ff, #ff00cc);
                color: white;
                padding-top: 30px;
            }

            .sidebar h5 {
                font-weight: 700;
            }

            .sidebar a {
                color: white;
                display: block;
                padding: 12px 20px;
                text-decoration: none;
                border-radius: 8px;
                margin: 5px 10px;
                transition: 0.3s;
            }

            .sidebar a:hover {
                background: rgba(255,255,255,0.2);
            }

            /* Cards */
            .card {
                border: none;
                border-radius: 20px;
                box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            }

            /* Gradient Buttons */
            .btn-gradient {
                background: linear-gradient(to right, #00c6ff, #ff00cc);
                border: none;
                color: white;
                border-radius: 30px;
                font-weight: 600;
                padding: 8px 20px;
                transition: 0.3s;
            }

            .btn-gradient:hover {
                opacity: 0.9;
            }

            /* Form Controls */
            .form-control, .form-select {
                border-radius: 10px;
                border: 1px solid #ddd;
            }

            .form-control:focus, .form-select:focus {
                border-color: #00c6ff;
                box-shadow: 0 0 0 0.2rem rgba(0,198,255,0.25);
            }

            /* Table Styling */
            table {
                border-radius: 20px;
                overflow: hidden;
            }

            thead {
                background: linear-gradient(to right, #00c6ff, #ff00cc);
                color: white;
            }

            .badge {
                padding: 6px 12px;
                border-radius: 20px;
                font-weight: 500;
            }
            .pagination li {
                margin: 0 4px;
            }
        </style>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <?php if(session()->get('logged_in')): ?>
                    <?= view('layouts/sidebar') ?>
                <?php endif; ?>
                <div class="col p-4">
                    <?= $this->renderSection('content') ?>
                </div>
            </div>
        </div>
    </body>
</html>
