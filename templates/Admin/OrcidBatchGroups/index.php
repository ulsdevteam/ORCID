<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidBatchGroup[]|\Cake\Collection\CollectionInterface $orcidBatchGroups
 */
?>
<div class="orcidBatchGroups index content">
    <h3><?= __('Orcid Batch Groups') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th><?= $this->Paginator->sort('group_definition', 'Active Directory') ?></th>
                    <th><?= $this->Paginator->sort('employee_definition', 'CDS Employee') ?></th>
                    <th><?= $this->Paginator->sort('student_definition', "CDS Student") ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orcidBatchGroups as $orcidBatchGroup): ?>
                <tr>
                    <td><?= h($orcidBatchGroup->name) ?></td>
                    <td><?= h($orcidBatchGroup->group_definition ? "Yes" : '') ?></td>
                    <td><?= h($orcidBatchGroup->employee_definition ? "Yes" : '') ?></td>
                    <td><?= h($orcidBatchGroup->student_definition ? "Yes" : '') ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $orcidBatchGroup->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $orcidBatchGroup->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $orcidBatchGroup->id], ['confirm' => __('Are you sure you want to delete # {0}?', $orcidBatchGroup->id)]) ?>
                        <?= $this->Html->link(__('List Users'), ['controller' => 'OrcidUsers', 'action' => 'find', 'prefix' => 'Admin', '?' => ['g' => $orcidBatchGroup->id]]); ?>
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
    <?= $this->Html->link(__('New Orcid Batch'), ['action' => 'add']) ?>
    <h3 class="heading"><?= __('Navigation') ?></h3>
    <?= $this->Html->link(__('Home'), ['controller' => '', 'action' => 'admin', 'prefix' => false]) ?>
</div>
