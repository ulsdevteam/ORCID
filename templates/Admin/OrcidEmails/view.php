<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidEmail $orcidEmail
 */
$this->assign('title', 'Scheduled Emails');
?>
<aside class="column">
    <nav>
        <div class="navigation actions">
            <h3 class="heading"><?= __('Actions') ?></h3>
            <?= $this->Form->postLink(__('Requeue'), ['action' => 'requeue', $orcidEmail->ID], ['confirm' => __('Are you sure you want requeue this email')]) ?>
            <?= $this->Form->postLink(__('Requeue'), ['action' => 'cancel', $orcidEmail->ID], ['confirm' => __('Are you sure you want to cancel this email')]) ?>
            <?= $this->Form->postLink(__('Send Orcid Email'), ['action' => 'send', $orcidEmail->ID], ['confirm' => __('Are you sure you want to send "{0}"?', $orcidEmail->ID)]) ?>
            <h3 class="heading"><?= __('Navigation') ?></h3>
            <?= $this->Html->link(__('List Orcid Emails'), ['action' => 'index']) ?>
            <?= $this->Html->link(__('Home'), ['controller' => '', 'action' => 'admin', 'prefix' => false]) ?>
        </div>
    </nav>
</aside>
<div class="row">
    <div class="column-responsive column-80">
        <div class="orcidEmails view content">
            <h2><?= h('Scheduled Email') ?></h2>
            <table>
                <tr>
                    <th><?= __('ORCID User') ?></th>
                    <td><?= $orcidEmail->has('orcid_user') ? $this->Html->link($orcidEmail->orcid_user->USERNAME, ['controller' => 'OrcidUsers', 'action' => 'view', $orcidEmail->orcid_user->ID]) : '' ?>
                    </td>
                </tr>
                <tr>
                    <th><?= __('ORCID Batch') ?></th>
                    <td><?= $orcidEmail->has('orcid_batch') ? $this->Html->link($orcidEmail->orcid_batch->NAME, ['controller' => 'OrcidBatches', 'action' => 'view', $orcidEmail->orcid_batch->ID]) : '' ?>
                    </td>
                </tr>
                <tr>
                    <th><?= __('Queued') ?></th>
                    <td><?= h($orcidEmail->QUEUED) ?></td>
                </tr>
                <tr>
                    <th><?= __('Sent') ?></th>
                    <td><?= h($orcidEmail->SENT) ?></td>
                </tr>
                <tr>
                    <th><?= __('Cancelled') ?></th>
                    <td><?= h($orcidEmail->CANCELLED) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>