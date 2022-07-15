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
            <h3><?= h($orcidBatchTrigger->NAME) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($orcidBatchTrigger->NAME) ?></td>
                </tr>
                <tr>
                    <th><?= __('Group') ?></th>
                    <td><?= $orcidBatchTrigger->has('orcid_batch_group') ? $this->Html->link($orcidBatchTrigger->orcid_batch_group->NAME, ['controller' => 'OrcidBatchGroups', 'action' => 'view', $orcidBatchTrigger->orcid_batch_group->ID]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Workflow Checkpoint') ?></th>
                    <td><?= $orcidBatchTrigger->has('orcid_status_type') ? $this->Html->link($orcidBatchTrigger->orcid_status_type->NAME, ['controller' => 'OrcidStatusTypes', 'action' => 'view', $orcidBatchTrigger->orcid_status_type->ID]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Email Batch') ?></th>
                    <td><?= $orcidBatchTrigger->has('orcid_batch') ? $this->Html->link($orcidBatchTrigger->orcid_batch->NAME, ['controller' => 'OrcidBatches', 'action' => 'view', $orcidBatchTrigger->orcid_batch->ID]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Trigger Delay') ?></th>
                    <td><?= $this->Number->format($orcidBatchTrigger->TRIGGER_DELAY) ?></td>
                </tr>
                <tr>
                    <th><?= __('Repeat Every') ?></th>
                    <td><?= $this->Number->format($orcidBatchTrigger->REPEAT) ?></td>
                </tr>
                <tr>
                    <th><?= __('Repeat Limit') ?></th>
                    <td><?= $this->Number->format($orcidBatchTrigger->MAXIMUM_REPEAT) ?></td>
                </tr>
                <tr>
                    <th><?= __('Begin Date') ?></th>
                    <td><?= h($orcidBatchTrigger->BEGIN_DATE) ?></td>
                </tr>
                <tr>
                    <th><?= __('Require Prior Batch') ?></th>
                    <td><?= h($orcidBatchTrigger->REQUIRE_BATCH_ID) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div class="navigation actions">
    <h3 class="heading"><?= __('Actions') ?></h3>
        <?= $this->Html->link(__('Edit Orcid Batch Trigger'), ['action' => 'edit', $orcidBatchTrigger->ID], ['class' => 'side-nav-item']) ?>
        <?= $this->Form->postLink(__('Delete Orcid Batch Trigger'), ['action' => 'delete', $orcidBatchTrigger->ID], ['confirm' => __('Are you sure you want to delete "{0}"?', $orcidBatchTrigger->NAME), 'class' => 'side-nav-item']) ?>
        <?= $this->Form->postLink(__('Execute'), ['action' => 'execute', $orcidBatchTrigger->ID], ['confirm' => __('Are you sure you want to execute "{0}"?', $orcidBatchTrigger->NAME)]) ?>
    <h3 class="heading"><?= __('Navigation') ?></h3>
        <?= $this->Html->link(__('List Triggers'), ['action' => 'index']) ?>
        <?= $this->Html->link(__('Home'), ['controller' => '', 'action' => 'admin', 'prefix' => false]) ?>
</div>