<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidUser[]|\Cake\Collection\CollectionInterface $orcidUsers
 */
?>
<div class="orcidUsers index content">
    <h3><?= __('Orcid Users') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('USERNAME') ?></th>
                    <th><?= $this->Paginator->sort('ORCID') ?></th>
                    <th><?= $this->Paginator->sort('TOKEN') ?></th>
                    <th><?= $this->Paginator->sort('CREATED') ?></th>
                    <th><?= $this->Paginator->sort('MODIFIED') ?></th
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orcidUsers as $orcidUser): ?>
                <tr>
                    <td><?= h(($orcidUser->USERNAME)) ?></td>
                    <td><?= h($orcidUser->ORCID) ?></td>
                    <td><?= h($orcidUser->TOKEN) ?></td>
                    <td><?= h($orcidUser->CREATED) ?></td>
                    <td><?= h($orcidUser->MODIFIED) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $orcidUser->ID]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $orcidUser->ID]) ?>
                        <?= $this->Form->postLink(__('Opt Out'), ['action' => 'optout', $orcidUser->ID], ['confirm' => __('Are you sure you want to opt out {0}?', $orcidUser->username)]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $orcidUser->ID], ['confirm' => __('Are you sure you want to delete {0}?', $orcidUser->username)]) ?>
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
    <?= $this->Html->link(__('New Orcid User'), ['action' => 'add']) ?>
    <h3 class="heading"><?= __('Navigation') ?></h3>
    <?= $this->Html->link(__('Find Orcid User'), ['action' => 'find']) ?>
    <?= $this->Html->link(__('Home'), ['controller' => '', 'action' => 'admin', 'prefix' => false]) ?>
</div>  