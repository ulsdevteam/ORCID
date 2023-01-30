<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidBatchTrigger[]|\Cake\Collection\CollectionInterface $orcidBatchTriggers
 */
$this->assign('title', 'Triggers');
?>
<aside class="column">
    <nav>
        <div class="navigation actions">
            <h3 class="heading"><?= __('Actions') ?></h3>
            <?= $this->Html->link(__('New Orcid Batch Trigger'), ['action' => 'add']) ?>
            <?= $this->Form->postLink(__('Execute All Triggers'), ['action' => 'executeAll'], ['confirm' => __('Are you sure you want to execute all triggers?')]) ?>
            <?= $this->Form->postLink(__('Send All Emails'), ['controller' => 'OrcidEmails', 'action' => 'sendAll'], ['confirm' => __('Are you sure you want to send all Triggered Emails?')]) ?>
            <h3 class="heading"><?= __('Navigation') ?></h3>
            <?= $this->Html->link(__('List Groups'), ['controller' => 'OrcidBatchGroups', 'action' => 'index']) ?>
            <?= $this->Html->link(__('Home'), ['controller' => '', 'action' => 'admin', 'prefix' => false]) ?>
        </div>
    </nav>
</aside>
<div class="orcidBatchTriggers index content">
    <h2><?= __('Triggers') ?></h2>
    <p>Each trigger has the potential to queue an email to a user based on the criteria specified in the trigger.</p>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('NAME', 'Name') ?></th>
                    <th><?= $this->Paginator->sort('ORCID_BATCH_GROUP_ID`', 'Group') ?></th>
                    <th><?= $this->Paginator->sort('ORCID_STATUS_TYPE_ID', 'Workflow Checkpoint') ?></th>
                    <th><?= $this->Paginator->sort('orcid_batch_id', 'Email Batch') ?></th>
                    <th><?= $this->Paginator->sort('TRIGGER_DELAY', 'Trigger Delay') ?></th>
                    <th><?= $this->Paginator->sort('REPEAT_VALUE', 'Repeat') ?></th>
                    <th><?= $this->Paginator->sort('MAXIMUM_REPEAT', 'Repeat Limit') ?></th>
                    <th><?= $this->Paginator->sort('BEGIN_DATE', 'Begin Date') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orcidBatchTriggers as $orcidBatchTrigger) : ?>
                    <tr>
                        <td><?= h($orcidBatchTrigger->NAME) ?></td>
                        <td><?= $orcidBatchTrigger->has('orcid_batch_group') ? $this->Html->link($orcidBatchTrigger->orcid_batch_group->NAME, ['controller' => 'OrcidStatusTypes', 'action' => 'view', $orcidBatchTrigger->orcid_batch_group->ID]) : '' ?>
                        </td>
                        <td><?= $orcidBatchTrigger->has('orcid_status_type') ? $this->Html->link($orcidBatchTrigger->orcid_status_type->NAME, ['controller' => 'OrcidStatusTypes', 'action' => 'view', $orcidBatchTrigger->orcid_status_type->ID]) : '' ?>
                        </td>
                        <td><?= $orcidBatchTrigger->has('orcid_batch') ? $this->Html->link($orcidBatchTrigger->orcid_batch->NAME, ['controller' => 'OrcidBatches', 'action' => 'view', $orcidBatchTrigger->orcid_batch->ID]) : '' ?>
                        </td>
                        <td><?= $this->Number->format($orcidBatchTrigger->TRIGGER_DELAY) ?></td>
                        <td><?= $this->Number->format($orcidBatchTrigger->REPEAT) ?></td>
                        <td><?= $this->Number->format($orcidBatchTrigger->MAXIMUM_REPEAT) ?></td>
                        <td><?= h($orcidBatchTrigger->BEGIN_DATE) ?></td>
                        <td class="actions">
                            <?= $this->Html->link(__('View'), ['action' => 'view', $orcidBatchTrigger->ID]) ?>
                            <?= $this->Html->link(__('Edit'), ['action' => 'edit', $orcidBatchTrigger->ID]) ?>
                            <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $orcidBatchTrigger->ID], ['confirm' => __('Are you sure you want to delete "{0}"?', $orcidBatchTrigger->NAME)]) ?>
                            <?= $this->Form->postLink(__('Execute'), ['action' => 'execute', $orcidBatchTrigger->ID], ['confirm' => __('Are you sure you want to execute "{0}"?', $orcidBatchTrigger->NAME)]) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?= $this->element('paginator/pagination'); ?>
</div>