<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidBatchTrigger $orcidBatchTrigger
 */
?>
<div class="row">
    <div class="column-responsive column-80">
        <div class="orcidBatchTriggers view content">
            <div>The User must be at the Workflow Checkpoint for Trigger Delay days and not have already been sent the Email Batch (or must have been sent the Email Batch at least Repeat Every days ago if Repeat Every is non-zero).  The User must match the Group criteria (if provided), and the User must have already received a prior email as specified by Require Prior Batch.  Today's date must be after the Begin Date (if provided).   The number of times this Email Batch has been sent to this user must not repeat more than Repeat Limit times (if provided).</div>
            <h3><?= h($orcidBatchTrigger->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($orcidBatchTrigger->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Group') ?></th>
                    <td><?= $orcidBatchTrigger->has('orcid_batch_group') ? $this->Html->link($orcidBatchTrigger->orcid_batch_group->name, ['controller' => 'OrcidBatchGroups', 'action' => 'view', $orcidBatchTrigger->orcid_batch_group->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Workflow Checkpoint') ?></th>
                    <td><?= $orcidBatchTrigger->has('orcid_status_type') ? $this->Html->link($orcidBatchTrigger->orcid_status_type->name, ['controller' => 'OrcidStatusTypes', 'action' => 'view', $orcidBatchTrigger->orcid_status_type->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Email Batch') ?></th>
                    <td><?= $orcidBatchTrigger->has('orcid_batch') ? $this->Html->link($orcidBatchTrigger->orcid_batch->name, ['controller' => 'OrcidBatches', 'action' => 'view', $orcidBatchTrigger->orcid_batch->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Trigger Delay') ?></th>
                    <td><?= $this->Number->format($orcidBatchTrigger->trigger_delay) ?></td>
                </tr>
                <tr>
                    <th><?= __('Repeat Every') ?></th>
                    <td><?= $this->Number->format($orcidBatchTrigger->repeat_value) ?></td>
                </tr>
                <tr>
                    <th><?= __('Repeat Limit') ?></th>
                    <td><?= $this->Number->format($orcidBatchTrigger->maximum_repeat) ?></td>
                </tr>
                <tr>
                    <th><?= __('Begin Date') ?></th>
                    <td><?= h($orcidBatchTrigger->begin_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Require Prior Batch') ?></th>
                    <td><?= h($orcidBatchTrigger->require_batch_id) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div class="navigation actions">
    <h3 class="heading"><?= __('Actions') ?></h3>
        <?= $this->Html->link(__('Edit Orcid Batch Trigger'), ['action' => 'edit', $orcidBatchTrigger->id], ['class' => 'side-nav-item']) ?>
        <?= $this->Form->postLink(__('Delete Orcid Batch Trigger'), ['action' => 'delete', $orcidBatchTrigger->id], ['confirm' => __('Are you sure you want to delete "{0}"?', $orcidBatchTrigger->name), 'class' => 'side-nav-item']) ?>
        <?= $this->Form->postLink(__('Execute'), ['action' => 'execute', $orcidBatchTrigger->id], ['confirm' => __('Are you sure you want to execute "{0}"?', $orcidBatchTrigger->name)]) ?>
    <h3 class="heading"><?= __('Navigation') ?></h3>
        <?= $this->Html->link(__('List Triggers'), ['action' => 'index']) ?>
        <?= $this->Html->link(__('Home'), ['controller' => '', 'action' => 'admin', 'prefix' => false]) ?>
</div>