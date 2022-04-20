<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidEmail[]|\Cake\Collection\CollectionInterface $orcidEmails
 */
?>
<div class="orcidEmails index content">
    <?= $this->Html->link(__('New Orcid Email'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Orcid Emails') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('orcid_user_id') ?></th>
                    <th><?= $this->Paginator->sort('orcid_batch_id') ?></th>
                    <th><?= $this->Paginator->sort('queued') ?></th>
                    <th><?= $this->Paginator->sort('sent') ?></th>
                    <th><?= $this->Paginator->sort('cancelled') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orcidEmails as $orcidEmail): ?>
                <tr>
                    <td><?= $this->Number->format($orcidEmail->id) ?></td>
                    <td><?= $orcidEmail->has('orcid_user') ? $this->Html->link($orcidEmail->orcid_user->id, ['controller' => 'OrcidUsers', 'action' => 'view', $orcidEmail->orcid_user->id]) : '' ?></td>
                    <td><?= $orcidEmail->has('orcid_batch') ? $this->Html->link($orcidEmail->orcid_batch->name, ['controller' => 'OrcidBatches', 'action' => 'view', $orcidEmail->orcid_batch->id]) : '' ?></td>
                    <td><?= h($orcidEmail->queued) ?></td>
                    <td><?= h($orcidEmail->sent) ?></td>
                    <td><?= h($orcidEmail->cancelled) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $orcidEmail->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $orcidEmail->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $orcidEmail->id], ['confirm' => __('Are you sure you want to delete # {0}?', $orcidEmail->id)]) ?>
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
