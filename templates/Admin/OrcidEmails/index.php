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
                    <th><?= $this->Paginator->sort('ID', 'Id') ?></th>
                    <th><?= $this->Paginator->sort('ORCID_USER_ID', 'Orcid User') ?></th>
                    <th><?= $this->Paginator->sort('ORCID_BATCH_ID', 'Orcid Batch') ?></th>
                    <th><?= $this->Paginator->sort('QUEUED', 'Queued') ?></th>
                    <th><?= $this->Paginator->sort('SENT', 'Sent') ?></th>
                    <th><?= $this->Paginator->sort('CANCELLED', 'Cancelled') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orcidEmails as $orcidEmail) : ?>
                    <tr>
                        <td><?= $this->Number->format($orcidEmail->ID) ?></td>
                        <td><?= $orcidEmail->has('orcid_user') ? $this->Html->link($orcidEmail->orcid_user->USERNAME, ['controller' => 'OrcidUsers', 'action' => 'view', $orcidEmail->orcid_user->ID]) : '' ?>
                        </td>
                        <td><?= $orcidEmail->has('orcid_batch') ? $this->Html->link($orcidEmail->orcid_batch->NAME, ['controller' => 'OrcidBatches', 'action' => 'view', $orcidEmail->orcid_batch->ID]) : '' ?>
                        </td>
                        <td><?= h($orcidEmail->QUEUED) ?></td>
                        <td><?= h($orcidEmail->SENT) ?></td>
                        <td><?= h($orcidEmail->CANCELLED) ?></td>
                        <td class="actions">
                            <?= $this->Html->link(__('View'), ['action' => 'view', $orcidEmail->ID]) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?= $this->element('paginator/pagination'); ?>
</div>