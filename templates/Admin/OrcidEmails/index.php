<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidEmail[]|\Cake\Collection\CollectionInterface $orcidEmails
 */
$this->assign('title', 'Scheduled Emails');
?>
<aside class="column">
    <nav>
        <div class="navigation actions">
            <h3 class="heading"><?= __('Navigation') ?></h3>
            <?= $this->Html->link(__('Home'), ['controller' => '', 'action' => 'admin', 'prefix' => false]) ?>
        </div>
    </nav>
</aside>
<div class="orcidEmails index content">
    <h2><?= __('ORCID Emails') ?></h2>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('ID', 'Id') ?></th>
                    <th><?= $this->Paginator->sort('ORCID_USER_ID', 'ORCID User') ?></th>
                    <th><?= $this->Paginator->sort('ORCID_BATCH_ID', 'ORCID Batch') ?></th>
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