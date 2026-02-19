<?php if (!empty($requests)): ?>
    <?php foreach($requests as $r): ?>
        <tr>
            <td class="ps-5"><?= esc($r['title']) ?></td>
            <td><?= esc($r['category']) ?></td>
            <td>
                <?php
                    $priorityColor = match($r['priority']) {
                        'Low' => 'secondary',
                        'Medium' => 'warning',
                        'High' => 'danger',
                        default => 'secondary'
                    };
                ?>
                <span class="badge bg-<?= $priorityColor ?>">
                    <?= esc($r['priority']) ?>
                </span>
            </td>
            <td>
                <?php
                    $statusColor = match($r['status']) {
                        'Submitted' => 'primary',
                        'Approved' => 'success',
                        'Rejected' => 'danger',
                        'Needs Clarification' => 'warning',
                        default => 'secondary'
                    };
                ?>
                <span class="badge bg-<?= $statusColor ?> statusBadge">
                    <?= esc($r['status']) ?>
                </span>
            </td>
            <td class="actionCell">
                <button class="btn btn-success btn-sm actionBtn" data-id="<?= $r['id'] ?>" data-action="Approved">Approve</button>
                <button class="btn btn-warning btn-sm actionBtn" data-id="<?= $r['id'] ?>" data-action="Needs Clarification">Clarify</button>
                <button class="btn btn-danger btn-sm actionBtn" data-id="<?= $r['id'] ?>"  data-action="Rejected">Reject</button>
            </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="5" class="text-center text-muted py-4">
            No Requests Found
        </td>
    </tr>
<?php endif; ?>