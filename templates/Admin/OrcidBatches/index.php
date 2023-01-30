<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidBatch[]|\Cake\Collection\CollectionInterface $orcidBatches
 */
$this->assign('title', 'Batch Email Templates');
?>
<aside class="column">
    <nav>
        <div class="navigation actions">
            <h3 class="heading"><?= __('Actions') ?></h3>
            <?= $this->Html->link(__('New Batch Email Template'), ['action' => 'add']) ?>
            <h3 class="heading"><?= __('Navigation') ?></h3>
            <?= $this->Html->link(__('Home'), ['controller' => '', 'action' => 'admin', 'prefix' => false]) ?>
        </div>
    </nav>
</aside>
<div class="orcidBatches index content">
    <h2><?= __('Batch Email Templates') ?></h2>
    <p>
        <?= __('An email template will be used to send a message to a user based on one or more triggers. Each email template can be sent to any user only once, unless manually re-queued.'); ?>
    </p>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('NAME', 'Name') ?></th>
                    <th><?= $this->Paginator->sort('FROM_NAME', 'From Name') ?></th>
                    <th><?= $this->Paginator->sort('FROM_ADDR', 'From Addr') ?></th>
                    <th><?= $this->Paginator->sort('SUBJECT', 'Subject') ?></th>
                    <th><?= $this->Paginator->sort('REPLY_TO', 'Reply To') ?></th>
                    <th><?= $this->Paginator->sort('ORCID_BATCH_CREATOR_ID', 'Creator') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orcidBatches as $orcidBatch) : ?>
                    <tr>
                        <td><?= h($orcidBatch->NAME) ?></td>
                        <td><?= h($orcidBatch->FROM_NAME) ?></td>
                        <td><?= h($orcidBatch->FROM_ADDR) ?></td>
                        <td><?= h($orcidBatch->SUBJECT) ?></td>
                        <td><?= h($orcidBatch->REPLY_TO) ?></td>
                        <td><?= $orcidBatch->has('orcid_batch_creator') ? $this->Html->link($orcidBatch->orcid_batch_creator->NAME, ['controller' => 'OrcidBatchCreators', 'action' => 'view', $orcidBatch->orcid_batch_creator->ID]) : '' ?>
                        </td>
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
    <?= $this->element('paginator/pagination'); ?>
</div>