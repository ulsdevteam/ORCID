<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidBatchTrigger[]|\Cake\Collection\CollectionInterface $orcidBatchTriggers
 */
?>
<div class="orcidBatchTriggers index content">
    <h3><?= __('Orcid Batch Triggers') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th><?= $this->Paginator->sort('orcid_batch_group', 'Group') ?></th>
                    <th><?= $this->Paginator->sort('orcid_status_type_id', 'Workflow Checkpoint') ?></th>
                    <th><?= $this->Paginator->sort('orcid_batch_id', 'Email Batch') ?></th>
                    <th><?= $this->Paginator->sort('trigger_delay') ?></th>
                    <th><?= $this->Paginator->sort('repeat_value', 'repeat') ?></th>
                    <th><?= $this->Paginator->sort('maximum_repeat', 'Repeat Limit') ?></th>
                    <th><?= $this->Paginator->sort('begin_date') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orcidBatchTriggers as $orcidBatchTrigger): ?>
                <tr>
                    <td><?= h($orcidBatchTrigger->NAME) ?></td>
                    <td><?= $orcidBatchTrigger->has('orcid_batch_group') ? $this->Html->link($orcidBatchTrigger->orcid_batch_group->NAME, ['controller' => 'OrcidStatusTypes', 'action' => 'view', $orcidBatchTrigger->orcid_batch_group->ID]) : '' ?></td>
                    <td><?= $orcidBatchTrigger->has('orcid_status_type') ? $this->Html->link($orcidBatchTrigger->orcid_status_type->NAME, ['controller' => 'OrcidStatusTypes', 'action' => 'view', $orcidBatchTrigger->orcid_status_type->ID]) : '' ?></td>
                    <td><?= $orcidBatchTrigger->has('orcid_batch') ? $this->Html->link($orcidBatchTrigger->orcid_batch->NAME, ['controller' => 'OrcidBatches', 'action' => 'view', $orcidBatchTrigger->orcid_batch->ID]) : '' ?></td>
                    <td><?= $this->Number->format($orcidBatchTrigger->TRIGGER_DELAY) ?></td>
                    <td><?= $this->Number->format($orcidBatchTrigger->REPEAT) ?></td>
                    <td><?= $this->Number->format($orcidBatchTrigger->MAXIMUM_REPEAT) ?></td>
                    <td><?= h($orcidBatchTrigger->BEGIN_DATE) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $orcidBatchTrigger->ID]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $orcidBatchTrigger->ID]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $orcidBatchTrigger->ID], ['confirm' => __('Are you sure you want to delete # {0}?', $orcidBatchTrigger->ID)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>
<div class="navigation actions">
    <h3 class="heading"><?= __('Actions') ?></h3>
        <?= $this->Html->link(__('New Orcid Batch Trigger'), ['action' => 'add']) ?>
        <?= $this->Form->postLink(__('Execute All Triggers'), ['action' => 'executeAll'], ['confirm' => __('Are you sure you want to execute all triggers?')]) ?>
        <?= $this->Form->postLink(__('Send All Emails'), ['controller' => 'OrcidEmails', 'action' => 'sendAll'], ['confirm' => __('Are you sure you want to send all Triggered Emails?')]) ?>
    <h3 class="heading"><?= __('Navigation') ?></h3>
        <?= $this->Html->link(__('List Groups'), ['controller' => 'OrcidBatchGroups', 'action' => 'index']) ?>
        <?= $this->Html->link(__('Home'), ['controller' => '', 'action' => 'admin', 'prefix' => false]) ?>
</div>