<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidBatchTrigger[]|\Cake\Collection\CollectionInterface $orcidBatchTriggers
 */
?>
<div class="orcidBatchTriggers index content">
    <?= $this->Html->link(__('New Orcid Batch Trigger'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Orcid Batch Triggers') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th><?= $this->Paginator->sort('orcid_status_type_id') ?></th>
                    <th><?= $this->Paginator->sort('orcid_batch_id') ?></th>
                    <th><?= $this->Paginator->sort('trigger_delay') ?></th>
                    <th><?= $this->Paginator->sort('orcid_batch_group_id') ?></th>
                    <th><?= $this->Paginator->sort('begin_date') ?></th>
                    <th><?= $this->Paginator->sort('require_batch_id') ?></th>
                    <th><?= $this->Paginator->sort('repeat') ?></th>
                    <th><?= $this->Paginator->sort('maximum_repeat') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orcidBatchTriggers as $orcidBatchTrigger): ?>
                <tr>
                    <td><?= $this->Number->format($orcidBatchTrigger->id) ?></td>
                    <td><?= h($orcidBatchTrigger->name) ?></td>
                    <td><?= $orcidBatchTrigger->has('orcid_status_type') ? $this->Html->link($orcidBatchTrigger->orcid_status_type->name, ['controller' => 'OrcidStatusTypes', 'action' => 'view', $orcidBatchTrigger->orcid_status_type->id]) : '' ?></td>
                    <td><?= $orcidBatchTrigger->has('orcid_batch') ? $this->Html->link($orcidBatchTrigger->orcid_batch->name, ['controller' => 'OrcidBatches', 'action' => 'view', $orcidBatchTrigger->orcid_batch->id]) : '' ?></td>
                    <td><?= $this->Number->format($orcidBatchTrigger->trigger_delay) ?></td>
                    <td><?= $orcidBatchTrigger->has('orcid_batch_group') ? $this->Html->link($orcidBatchTrigger->orcid_batch_group->name, ['controller' => 'OrcidBatchGroups', 'action' => 'view', $orcidBatchTrigger->orcid_batch_group->id]) : '' ?></td>
                    <td><?= h($orcidBatchTrigger->begin_date) ?></td>
                    <td><?= $orcidBatchTrigger->require_batch_id === null ? '' : $this->Number->format($orcidBatchTrigger->require_batch_id) ?></td>
                    <td><?= $this->Number->format($orcidBatchTrigger->repeat) ?></td>
                    <td><?= $this->Number->format($orcidBatchTrigger->maximum_repeat) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $orcidBatchTrigger->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $orcidBatchTrigger->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $orcidBatchTrigger->id], ['confirm' => __('Are you sure you want to delete # {0}?', $orcidBatchTrigger->id)]) ?>
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
