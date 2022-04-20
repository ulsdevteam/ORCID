<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidBatchGroup[]|\Cake\Collection\CollectionInterface $orcidBatchGroups
 */
?>
<div class="orcidBatchGroups index content">
    <?= $this->Html->link(__('New Orcid Batch Group'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Orcid Batch Groups') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th><?= $this->Paginator->sort('group_definition') ?></th>
                    <th><?= $this->Paginator->sort('employee_definition') ?></th>
                    <th><?= $this->Paginator->sort('student_definition') ?></th>
                    <th><?= $this->Paginator->sort('cache_creation_date') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orcidBatchGroups as $orcidBatchGroup): ?>
                <tr>
                    <td><?= $this->Number->format($orcidBatchGroup->id) ?></td>
                    <td><?= h($orcidBatchGroup->name) ?></td>
                    <td><?= h($orcidBatchGroup->group_definition) ?></td>
                    <td><?= h($orcidBatchGroup->employee_definition) ?></td>
                    <td><?= h($orcidBatchGroup->student_definition) ?></td>
                    <td><?= h($orcidBatchGroup->cache_creation_date) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $orcidBatchGroup->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $orcidBatchGroup->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $orcidBatchGroup->id], ['confirm' => __('Are you sure you want to delete # {0}?', $orcidBatchGroup->id)]) ?>
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
