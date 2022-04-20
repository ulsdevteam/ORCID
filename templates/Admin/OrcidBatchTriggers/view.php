<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidBatchTrigger $orcidBatchTrigger
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Orcid Batch Trigger'), ['action' => 'edit', $orcidBatchTrigger->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Orcid Batch Trigger'), ['action' => 'delete', $orcidBatchTrigger->id], ['confirm' => __('Are you sure you want to delete # {0}?', $orcidBatchTrigger->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Orcid Batch Triggers'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Orcid Batch Trigger'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="orcidBatchTriggers view content">
            <h3><?= h($orcidBatchTrigger->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($orcidBatchTrigger->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Orcid Status Type') ?></th>
                    <td><?= $orcidBatchTrigger->has('orcid_status_type') ? $this->Html->link($orcidBatchTrigger->orcid_status_type->name, ['controller' => 'OrcidStatusTypes', 'action' => 'view', $orcidBatchTrigger->orcid_status_type->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Orcid Batch') ?></th>
                    <td><?= $orcidBatchTrigger->has('orcid_batch') ? $this->Html->link($orcidBatchTrigger->orcid_batch->name, ['controller' => 'OrcidBatches', 'action' => 'view', $orcidBatchTrigger->orcid_batch->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Orcid Batch Group') ?></th>
                    <td><?= $orcidBatchTrigger->has('orcid_batch_group') ? $this->Html->link($orcidBatchTrigger->orcid_batch_group->name, ['controller' => 'OrcidBatchGroups', 'action' => 'view', $orcidBatchTrigger->orcid_batch_group->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($orcidBatchTrigger->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Trigger Delay') ?></th>
                    <td><?= $this->Number->format($orcidBatchTrigger->trigger_delay) ?></td>
                </tr>
                <tr>
                    <th><?= __('Require Batch Id') ?></th>
                    <td><?= $orcidBatchTrigger->require_batch_id === null ? '' : $this->Number->format($orcidBatchTrigger->require_batch_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Repeat') ?></th>
                    <td><?= $this->Number->format($orcidBatchTrigger->repeat) ?></td>
                </tr>
                <tr>
                    <th><?= __('Maximum Repeat') ?></th>
                    <td><?= $this->Number->format($orcidBatchTrigger->maximum_repeat) ?></td>
                </tr>
                <tr>
                    <th><?= __('Begin Date') ?></th>
                    <td><?= h($orcidBatchTrigger->begin_date) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
