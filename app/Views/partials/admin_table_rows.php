<?php if (!empty($requests)): ?>
    <?php foreach($requests as $r): ?>
        <tr>
            <td class="ps-5"><?= esc($r['title']) ?></td>
            <td><?= ucfirst(esc($r['role'])) ?></td>
            <td>
                <?php
                    $color = match($r['status']) {
                        'Submitted' => 'primary',
                        'Approved' => 'success',
                        'Rejected' => 'danger',
                        'Needs Clarification' => 'warning',
                        'Closed' => 'dark',
                        'Reopened' => 'info',
                        default => 'secondary'
                    };
                ?>
                <span class="badge bg-<?= $color ?> statusBadge">
                    <?= esc($r['status']) ?>
                </span>
            </td>
            <td><?= esc($r['updated_at']) ?></td>
            <td class="actionCell">

                <!-- View History -->
                <a href="<?= base_url('admin/request/'.$r['id'].'/history') ?>" class="btn btn-secondary btn-sm">View History</a>

                <!-- Close Button (Only if Approved) -->
                <?php if($r['status']=='Approved'): ?>
                    <button class="btn btn-dark btn-sm actionBtn" data-id="<?= $r['id'] ?>" data-action="Closed">Close</button>
                <?php endif; ?>

                <!-- Reopen Button (Only if Closed) -->
                <?php if($r['status']=='Closed'): ?>
                    <button class="btn btn-info btn-sm actionBtn" data-id="<?= $r['id'] ?>" data-action="Reopened">Reopen</button>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="5" class="text-center text-muted py-4">
            No Records Found
        </td>
    </tr>
<?php endif; ?>
