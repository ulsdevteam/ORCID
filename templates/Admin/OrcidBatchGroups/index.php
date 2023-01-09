<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidBatchGroup[]|\Cake\Collection\CollectionInterface $orcidBatchGroups
 */
$this->assign('title', 'Orcid Batch Groups');
?>
<aside class="column">
    <nav>
        <div class="navigation actions">
            <h3 class="heading"><?= __('Actions') ?></h3>
            <?= $this->Html->link(__('New Group'), ['action' => 'add']) ?>
            <h3 class="heading"><?= __('Navigation') ?></h3>
            <?= $this->Html->link(__('List Triggers'), ['controller' => 'orcid_batch_triggers','action' => 'index']) ?>
            <?= $this->Html->link(__('Home'), ['controller' => '', 'action' => 'admin', 'prefix' => false]) ?>
        </div>
    </nav>
</aside>
<div class="orcidBatchGroups index content">
    <h3><?= __('ORCID Batch Groups') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('NAME', 'Name') ?></th>
                    <th><?= $this->Paginator->sort('GROUP_DEFINITION', 'Active Directory') ?></th>
                    <th><?= $this->Paginator->sort('EMPLOYEE_DEFINITION', 'CDS Employee') ?></th>
                    <th><?= $this->Paginator->sort('STUDENT_DEFINITION', "CDS Student") ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orcidBatchGroups as $orcidBatchGroup) : ?>
                    <tr>
                        <td><?= h($orcidBatchGroup->NAME) ?></td>
                        <td><?= h($orcidBatchGroup->GROUP_DEFINITION ? "Yes" : '') ?></td>
                        <td><?= h($orcidBatchGroup->EMPLOYEE_DEFINITION ? "Yes" : '') ?></td>
                        <td><?= h($orcidBatchGroup->STUDENT_DEFINITION ? "Yes" : '') ?></td>
                        <td class="actions">
                            <?= $this->Html->link(__('View'), ['action' => 'view', $orcidBatchGroup->ID]) ?>
                            <?= $this->Html->link(__('Edit'), ['action' => 'edit', $orcidBatchGroup->ID]) ?>
                            <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $orcidBatchGroup->ID], ['confirm' => __('Are you sure you want to delete # {0}?', $orcidBatchGroup->ID)]) ?>
                            <?= $this->Html->link(__('List Users'), ['controller' => 'OrcidUsers', 'action' => 'find', 'prefix' => 'Admin', '?' => ['g' => $orcidBatchGroup->ID]]); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?= $this->element('paginator/pagination'); ?>
</div>