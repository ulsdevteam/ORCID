<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidBatchGroupCache $orcidBatchGroupCache
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Orcid Batch Group Cache'), ['action' => 'edit', $orcidBatchGroupCache->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Orcid Batch Group Cache'), ['action' => 'delete', $orcidBatchGroupCache->id], ['confirm' => __('Are you sure you want to delete # {0}?', $orcidBatchGroupCache->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Orcid Batch Group Caches'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Orcid Batch Group Cache'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="orcidBatchGroupCaches view content">
            <h3><?= h($orcidBatchGroupCache->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Orcid Batch Group') ?></th>
                    <td><?= $orcidBatchGroupCache->has('orcid_batch_group') ? $this->Html->link($orcidBatchGroupCache->orcid_batch_group->name, ['controller' => 'OrcidBatchGroups', 'action' => 'view', $orcidBatchGroupCache->orcid_batch_group->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Orcid User') ?></th>
                    <td><?= $orcidBatchGroupCache->has('orcid_user') ? $this->Html->link($orcidBatchGroupCache->orcid_user->id, ['controller' => 'OrcidUsers', 'action' => 'view', $orcidBatchGroupCache->orcid_user->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($orcidBatchGroupCache->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Deprecated') ?></th>
                    <td><?= h($orcidBatchGroupCache->deprecated) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
