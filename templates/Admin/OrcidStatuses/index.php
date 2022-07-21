<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidStatus[]|\Cake\Collection\CollectionInterface $orcidStatuses
 */
?>
<div class="orcidStatuses index content">
    <?= $this->Html->link(__('New Orcid Status'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Orcid Statuses') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('orcid_user_id') ?></th>
                    <th><?= $this->Paginator->sort('orcid_status_type_id') ?></th>
                    <th><?= $this->Paginator->sort('status_timestamp') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orcidStatuses as $orcidStatus): ?>
                <tr>
                    <td><?= $this->Number->format($orcidStatus->ID) ?></td>
                    <td><?= $orcidStatus->has('orcid_user') ? $this->Html->link($orcidStatus->orcid_user->ID, ['controller' => 'OrcidUsers', 'action' => 'view', $orcidStatus->orcid_user->ID]) : '' ?></td>
                    <td><?= $orcidStatus->has('orcid_status_type') ? $this->Html->link($orcidStatus->orcid_status_type->name, ['controller' => 'OrcidStatusTypes', 'action' => 'view', $orcidStatus->orcid_status_type->ID]) : '' ?></td>
                    <td><?= h($orcidStatus->status_timestamp) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $orcidStatus->ID]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $orcidStatus->ID]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $orcidStatus->ID], ['confirm' => __('Are you sure you want to delete # {0}?', $orcidStatus->ID)]) ?>
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
    <h4 class="heading"><?= __('Actions') ?></h4>
    <?= $this->Html->link(__('List Orcid Status Types'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
    <?= $this->Html->link(__('Home'), ['controller' => '', 'action' => 'admin', 'prefix' => false]) ?>
</div>
