<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidEmail $orcidEmail
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(__('Delete Orcid Email'), ['action' => 'delete', $orcidEmail->ID], ['confirm' => __('Are you sure you want to delete # {0}?', $orcidEmail->ID), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Orcid Emails'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Orcid Email'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="orcidEmails view content">
            <h3><?= h($orcidEmail->ID) ?></h3>
            <table>
                <tr>
                    <th><?= __('Orcid User') ?></th>
                    <td><?= $orcidEmail->has('orcid_user') ? $this->Html->link($orcidEmail->orcid_user->ID, ['controller' => 'OrcidUsers', 'action' => 'view', $orcidEmail->orcid_user->ID]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Orcid Batch') ?></th>
                    <td><?= $orcidEmail->has('orcid_batch') ? $this->Html->link($orcidEmail->orcid_batch->NAME, ['controller' => 'OrcidBatches', 'action' => 'view', $orcidEmail->orcid_batch->ID]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($orcidEmail->ID) ?></td>
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
