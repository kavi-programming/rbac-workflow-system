<?php 
if (!empty($requests)): ?>
    <?php foreach($requests as $r): ?>
        <tr>
            <td class="ps-5"><?= esc($r['title']) ?></td>
            <td><?= esc($r['description']) ?></td>
            <td>
                <?php
                    $color = match($r['status']) {
                        'Submitted' => 'primary',
                        'Approved' => 'success',
                        'Rejected' => 'danger',
                        'Needs Clarification' => 'warning',
                        'Closed' => 'dark',
                        'Reopened' => 'info',
                    };
                ?>
                <span class="badge bg-<?= $color ?>">
                    <?= $r['status'] ?>
                </span>
            </td>
            <td><?= $r['updated_at'] ?></td>
            <td>
                <?php if ($r['status'] === 'Needs Clarification'): ?>
                    <a href="<?= base_url('request/edit/'.$r['id']) ?>" class="btn btn-sm btn-warning fw-bold">Edit</a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="4" class="text-center text-muted py-4">
            No Requests Found
        </td>
    </tr>
<?php endif; ?>
