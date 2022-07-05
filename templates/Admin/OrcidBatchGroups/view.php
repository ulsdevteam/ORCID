<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidBatchGroup $orcidBatchGroup
 */
?>
<div class="row">
    <div class="column-responsive column-80">
        <div class="orcidBatchGroups view content">
            <h3><?= h($orcidBatchGroup->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($orcidBatchGroup->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Active Directory Filter') ?></th>
                    <td><?= h($orcidBatchGroup->group_definition) ?></td>
                </tr>
                <tr>
                    <th><?= __('Central Directory Filters'); ?></th>
                    <td>
                        <table>
                            <tr>
                                <th><?= __('Employee Definition') ?></th>
                                <td><?= h($orcidBatchGroup->employee_definition) ?></td>
                            </tr>
                            <tr>
                                <th><?= __('Student Definition') ?></th>
                                <td><?= h($orcidBatchGroup->student_definition) ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <th><?= __('Cached') ?></th>
                    <td><?= h($orcidBatchGroup->cache_creation_date).' ('.$this->Number->format(count($orcidBatchGroup->orcid_batch_group_caches)).' records)' ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div class="navigation actions">
    <h3 class="heading"><?= __('Actions') ?></h3>
        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $orcidBatchGroup->id]) ?>
        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $orcidBatchGroup->id], ['confirm' => __('Are you sure you want to delete # {0}?', $orcidBatchGroup->id), 'class' => 'side-nav-item']) ?>
        <?= $this->Form->postLink(__('List Users'), ['controller' => 'OrcidUsers', 'action' => 'find', 'prefix' => 'Admin'], ['data' => ['g' => $orcidBatchGroup->id]]); ?>
        <?= $this->Form->postLink(__('Expire Cache '), ['action' => 'recache', $orcidBatchGroup->id]) ?>
    <h3 class="heading"><?= __('Navigation') ?></h3>
        <?= $this->Html->link(__('List Groups'), ['action' => 'index']) ?>
        <?= $this->Html->link(__('Home'), ['controller' => '', 'action' => 'admin', 'prefix' => false]) ?>
</div>