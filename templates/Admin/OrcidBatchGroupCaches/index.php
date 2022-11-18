<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidBatchGroupCache[]|\Cake\Collection\CollectionInterface $orcidBatchGroupCaches
 */
?>
<div class="orcidBatchGroupCaches index content">
    <?= $this->Html->link(__('New Orcid Batch Group Cache'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('ORCID Batch Group Caches') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('ID', 'Id') ?></th>
                    <th><?= $this->Paginator->sort('orcid_batch_group_id') ?></th>
                    <th><?= $this->Paginator->sort('orcid_user_id') ?></th>
                    <th><?= $this->Paginator->sort('DEPRECATED', 'Deprecated') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orcidBatchGroupCaches as $orcidBatchGroupCache) : ?>
                    <tr>
                        <td><?= $this->Number->format($orcidBatchGroupCache->ID) ?></td>
                        <td><?= $orcidBatchGroupCache->has('orcid_batch_group') ? $this->Html->link($orcidBatchGroupCache->orcid_batch_group->NAME, ['controller' => 'OrcidBatchGroups', 'action' => 'view', $orcidBatchGroupCache->orcid_batch_group->ID]) : '' ?>
                        </td>
                        <td><?= $orcidBatchGroupCache->has('orcid_user') ? $this->Html->link($orcidBatchGroupCache->orcid_user->USERNAME, ['controller' => 'OrcidUsers', 'action' => 'view', $orcidBatchGroupCache->orcid_user->ID]) : '' ?>
                        </td>
                        <td><?= h($orcidBatchGroupCache->DEPRECATED) ?></td>
                        <td class="actions">
                            <?= $this->Html->link(__('View'), ['action' => 'view', $orcidBatchGroupCache->ID]) ?>
                            <?= $this->Html->link(__('Edit'), ['action' => 'edit', $orcidBatchGroupCache->ID]) ?>
                            <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $orcidBatchGroupCache->ID], ['confirm' => __('Are you sure you want to delete # {0}?', $orcidBatchGroupCache->ID)]) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?= $this->element('paginator/pagination'); ?>
</div>