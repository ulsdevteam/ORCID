<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidBatchCreator[]|\Cake\Collection\CollectionInterface $orcidBatchCreators
 */
?>
<div class="orcidBatchCreators index content">
    <?= $this->Html->link(__('New Orcid Batch Creator'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Orcid Batch Creators') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th><?= $this->Paginator->sort('flags') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orcidBatchCreators as $orcidBatchCreator): ?>
                <tr>
                    <td><?= $this->Number->format($orcidBatchCreator->id) ?></td>
                    <td><?= h($orcidBatchCreator->name) ?></td>
                    <td><?= $this->Number->format($orcidBatchCreator->flags) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $orcidBatchCreator->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $orcidBatchCreator->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $orcidBatchCreator->id], ['confirm' => __('Are you sure you want to delete # {0}?', $orcidBatchCreator->id)]) ?>
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
