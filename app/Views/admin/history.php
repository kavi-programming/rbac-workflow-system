<h2>Request History</h2>
<table border="1" cellpadding="10">
    <tr>
        <th>Old Status</th>
        <th>New Status</th>
        <th>Changed By</th>
        <th>Role</th>
        <th>Time</th>
    </tr>

    <?php foreach($logs as $log): ?>
    <tr>
        <td><?= esc($log['old_status']) ?></td>
        <td><?= esc($log['new_status']) ?></td>
        <td><?= esc($log['changed_by']) ?></td>
        <td><?= esc($log['role']) ?></td>
        <td><?= esc($log['timestamp']) ?></td>
    </tr>
    <?php endforeach; ?>
</table>
