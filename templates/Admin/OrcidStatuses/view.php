<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidStatus $orcidStatus
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Orcid Status'), ['action' => 'edit', $orcidStatus->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Orcid Status'), ['action' => 'delete', $orcidStatus->id], ['confirm' => __('Are you sure you want to delete # {0}?', $orcidStatus->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Orcid Statuses'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Orcid Status'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="orcidStatuses view content">
            <h3><?= h($orcidStatus->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Orcid User') ?></th>
                    <td><?= $orcidStatus->has('orcid_user') ? $this->Html->link($orcidStatus->orcid_user->username, ['controller' => 'OrcidUsers', 'action' => 'view', $orcidStatus->orcid_user->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Orcid Status Type') ?></th>
                    <td><?= $orcidStatus->has('orcid_status_type') ? $this->Html->link($orcidStatus->orcid_status_type->name, ['controller' => 'OrcidStatusTypes', 'action' => 'view', $orcidStatus->orcid_status_type->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($orcidStatus->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Status Timestamp') ?></th>
                    <td><?= h($orcidStatus->status_timestamp) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
