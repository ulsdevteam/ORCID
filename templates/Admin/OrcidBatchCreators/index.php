<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidBatchCreator[]|\Cake\Collection\CollectionInterface $orcidBatchCreators
 */
$this->assign('title', 'Administrators');
?>
<aside class="column">
    <nav>
        <div class="navigation actions">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('New Administrator'), ['action' => 'add']) ?>
            <h3 class="heading"><?= __('Navigation') ?></h3>
            <?= $this->Html->link(__('Home'), ['controller' => '', 'action' => 'admin', 'prefix' => false]) ?>
        </div>
    </nav>
</aside>
<div class="orcidBatchCreators index content">
    <p><?= __('The following users are allowed to use this interface to administer email templates and triggers.') ?></p>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('NAME','Username') ?></th>
                    <th><?= 'Name' ?></th>
                    <th><?= $this->Paginator->sort('FLAGS','Enabled') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orcidBatchCreators as $orcidBatchCreator) : ?>
                    <tr>
                        <td><?= h($orcidBatchCreator->NAME) ?></td>
                        <td><?= h($orcidBatchCreator->DISPLAYNAME) ?></td>
                        <td><?= $orcidBatchCreator->flagStatus() ? __("No") : __("Yes") ?></td>
                        <td class="actions">
                            <?= $this->Html->link(__('View'), ['action' => 'view', $orcidBatchCreator->ID]) ?>
                            <?php if ($orcidBatchCreator->flagStatus()) : ?>
                                <?= $this->Form->postLink(__('Enable'), ['action' => 'enable', $orcidBatchCreator->ID]) ?>
                            <?php else : ?>
                                <?= $this->Form->postLink(__('Disable'), ['action' => 'disable', $orcidBatchCreator->ID]) ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?= $this->element('paginator/pagination'); ?>
    </div>
</div>