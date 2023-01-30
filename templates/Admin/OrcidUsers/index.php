<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidUser[]|\Cake\Collection\CollectionInterface $orcidUsers
 */
$this->assign('title', 'ORCID Users');
?>
<aside class="column">
    <nav>
        <div class="navigation actions">
            <h3 class="heading"><?= __('Actions') ?></h3>
            <?= $this->Html->link(__('New Orcid User'), ['action' => 'add']) ?>
            <h3 class="heading"><?= __('Navigation') ?></h3>
            <?= $this->Html->link(__('Find Orcid User'), ['action' => 'find']) ?>
            <?= $this->Html->link(__('Home'), ['controller' => '', 'action' => 'admin', 'prefix' => false]) ?>
        </div>
    </nav>
</aside>
<div class="orcidUsers index content">
    <h2><?= __('ORCID Users') ?></h2>
    <p>The following users are within the ORCID process. View the user to see details, including the current and historic checkpoints as well as scheduled email communication.</p>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('USERNAME', 'Username') ?></th>
                    <th><?= $this->Paginator->sort('ORCID', 'Orcid') ?></th>
                    <th><?= $this->Paginator->sort('TOKEN', 'Token') ?></th>
                    <th><?= $this->Paginator->sort('CREATED', 'Created') ?></th>
                    <th><?= $this->Paginator->sort('MODIFIED', 'Modified') ?></th>
                    <th><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orcidUsers as $orcidUser) : ?>
                    <tr>
                        <td><?= h($orcidUser->USERNAME) ?></td>
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
    <?= $this->element('paginator/pagination'); ?>
</div>