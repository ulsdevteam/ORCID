<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidStatusType[]|\Cake\Collection\CollectionInterface $orcidStatusTypes
 */
$this->assign('title', 'Workflow Checkpoints');
?>
<aside class="column">
    <nav>
        <div class="navigation actions">
            <h3 class="heading"><?= __('Navigation') ?></h3>
            <?= $this->Html->link(__('Home'), ['controller' => '', 'action' => 'admin', 'prefix' => false]) ?>
        </div>
    </nav>
</aside>
<div class="orcidStatusTypes index content">
    <h2><?= __($this->fetch('title')) ?></h2>
    <p>These checkpoints represent different stages of completion within the workflow, ordered in sequence.  View a checkpoint to see a list of users at this status in the workflow.</p>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('NAME', 'Name') ?></th>
                    <th><?= $this->Paginator->sort('SEQ', 'Seq', ['direction' => 'asc']) ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $orcidStatusTypes = $orcidStatusTypes->sortBy('SEQ', SORT_ASC); ?>
                <?php foreach ($orcidStatusTypes as $orcidStatusType) : ?>
                    <tr>
                        <td><?= h($orcidStatusType->NAME) ?></td>
                        <td><?= $orcidStatusType->SEQ === null ? '' : $this->Number->format($orcidStatusType->SEQ) ?>
                        </td>
                        <td class="actions">
                            <?= $this->Html->link(__('View'), ['action' => 'view', $orcidStatusType->ID]) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?= $this->element('paginator/pagination'); ?>
</div>