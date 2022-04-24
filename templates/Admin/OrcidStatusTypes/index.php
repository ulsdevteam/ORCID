<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidStatusType[]|\Cake\Collection\CollectionInterface $orcidStatusTypes
 */
?>
<div class="container">
    <div class="orcidStatusTypes index content">
        <?= $this->Html->link(__('New Orcid Status Type'), ['action' => 'add'], ['class' => 'button float-right']) ?>
        <h3><?= __('Orcid Status Types') ?></h3>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th><?= $this->Paginator->sort('name') ?></th>
                        <th><?= $this->Paginator->sort('seq') ?></th>
                        <th class="actions"><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orcidStatusTypes as $orcidStatusType): ?>
                    <tr>
                        <td><?= h($orcidStatusType->name) ?></td>
                        <td><?= $orcidStatusType->seq === null ? '' : $this->Number->format($orcidStatusType->seq) ?></td>
                        <td class="actions">
                            <?= $this->Html->link(__('View'), ['action' => 'view', $orcidStatusType->id]) ?>
                            <?= $this->Html->link(__('Edit'), ['action' => 'edit', $orcidStatusType->id]) ?>
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
        <h4 class="heading"><?= __('Actions') ?></h4>
        <?= $this->Html->link(__('List Orcid Status Types'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        <?= $this->Html->link(__('Home'), ['controller' => '', 'action' => 'admin', 'prefix' => false]) ?>
    </div>
</div>