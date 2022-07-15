<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidBatch[]|\Cake\Collection\CollectionInterface $orcidBatches
 */
?>
<div class="orcidBatches index content">
    <h3><?= __('Batch Email Templates') ?></h3>
    <?= __('
An email template will be used to send a message to a user based on one or more triggers. Each email template can be sent to any user only once, unless manually re-queued.'); ?>
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
                    <td><?= h($orcidBatch->NAME) ?></td>
                    <td><?= h($orcidBatch->FROM_NAME) ?></td>
                    <td><?= h($orcidBatch->FROM_ADDR) ?></td>
                    <td><?= h($orcidBatch->SUBJECT) ?></td>
                    <td><?= h($orcidBatch->REPLY_TO) ?></td>
                    <td><?= $orcidBatch->has('orcid_batch_creator') ? $this->Html->link($orcidBatch->orcid_batch_creator->NAME, ['controller' => 'OrcidBatchCreators', 'action' => 'view', $orcidBatch->orcid_batch_creator->ID]) : '' ?></td>
                    <td class="actions individual">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $orcidBatch->ID]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $orcidBatch->ID]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $orcidBatch->ID], ['confirm' => __('Are you sure you want to delete # {0}?', $orcidBatch->ID)]) ?>
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