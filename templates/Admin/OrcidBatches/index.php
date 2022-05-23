<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidBatch[]|\Cake\Collection\CollectionInterface $orcidBatches
 */
?>
<div class="orcidBatches index content">
    <h3><?= __('Orcid Batches') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th><?= $this->Paginator->sort('from_name') ?></th>
                    <th><?= $this->Paginator->sort('from_addr', 'From Address') ?></th>
                    <th><?= $this->Paginator->sort('subject') ?></th>
                    <th><?= $this->Paginator->sort('reply_to') ?></th>
                    <th><?= $this->Paginator->sort('orcid_batch_creator_id', 'Creator') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orcidBatches as $orcidBatch): ?>
                <tr>
                    <td><?= h($orcidBatch->name) ?></td>
                    <td><?= h($orcidBatch->from_name) ?></td>
                    <td><?= h($orcidBatch->from_addr) ?></td>
                    <td><?= h($orcidBatch->subject) ?></td>
                    <td><?= h($orcidBatch->reply_to) ?></td>
                    <td><?= $orcidBatch->has('orcid_batch_creator') ? $this->Html->link($orcidBatch->orcid_batch_creator->name, ['controller' => 'OrcidBatchCreators', 'action' => 'view', $orcidBatch->orcid_batch_creator->id]) : '' ?></td>
                    <td class="actions individual">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $orcidBatch->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $orcidBatch->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $orcidBatch->id], ['confirm' => __('Are you sure you want to delete # {0}?', $orcidBatch->id)]) ?>
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
<div class="actions">
    <h3 class="heading"><?= __('Actions') ?></h3>
    <?= $this->Html->link(__('New Orcid Batch'), ['action' => 'add']) ?>
    <h3 class="heading"><?= __('Navigation') ?></h3>
    <?= $this->Html->link(__('Home'), ['controller' => '', 'action' => 'admin', 'prefix' => false]) ?>
</div>