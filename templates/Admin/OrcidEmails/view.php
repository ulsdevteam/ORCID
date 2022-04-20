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
            <?= $this->Html->link(__('Edit Orcid Email'), ['action' => 'edit', $orcidEmail->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Orcid Email'), ['action' => 'delete', $orcidEmail->id], ['confirm' => __('Are you sure you want to delete # {0}?', $orcidEmail->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Orcid Emails'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Orcid Email'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="orcidEmails view content">
            <h3><?= h($orcidEmail->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Orcid User') ?></th>
                    <td><?= $orcidEmail->has('orcid_user') ? $this->Html->link($orcidEmail->orcid_user->id, ['controller' => 'OrcidUsers', 'action' => 'view', $orcidEmail->orcid_user->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Orcid Batch') ?></th>
                    <td><?= $orcidEmail->has('orcid_batch') ? $this->Html->link($orcidEmail->orcid_batch->name, ['controller' => 'OrcidBatches', 'action' => 'view', $orcidEmail->orcid_batch->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($orcidEmail->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Queued') ?></th>
                    <td><?= h($orcidEmail->queued) ?></td>
                </tr>
                <tr>
                    <th><?= __('Sent') ?></th>
                    <td><?= h($orcidEmail->sent) ?></td>
                </tr>
                <tr>
                    <th><?= __('Cancelled') ?></th>
                    <td><?= h($orcidEmail->cancelled) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
