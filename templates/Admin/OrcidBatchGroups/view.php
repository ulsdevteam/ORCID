<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidBatchGroup $orcidBatchGroup
 */
?>
<div class="row">
    <div class="column-responsive column-80">
        <div class="orcidBatchGroups view content">
            <h3><?= h($orcidBatchGroup->NAME) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($orcidBatchGroup->NAME) ?></td>
                </tr>
                <tr>
                    <th><?= __('Active Directory Filter') ?></th>
                    <td><?= h($orcidBatchGroup->GROUP_DEFINITION) ?></td>
                </tr>
                <tr>
                    <th><?= __('Central Directory Filters'); ?></th>
                    <td>
                        <table>
                            <tr>
                                <th><?= __('Employee Definition') ?></th>
                                <td><?= h($orcidBatchGroup->EMPLOYEE_DEFINITION) ?></td>
                            </tr>
                            <tr>
                                <th><?= __('Student Definition') ?></th>
                                <td><?= h($orcidBatchGroup->STUDENT_DEFINITION) ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <th><?= __('Cached') ?></th>
                    <td><?= h($orcidBatchGroup->CACHE_CREATION_DATE).' ('.$this->Number->format(count($orcidBatchGroup->orcid_batch_group_caches)).' records)' ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div class="navigation actions">
    <h3 class="heading"><?= __('Actions') ?></h3>
        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $orcidBatchGroup->ID]) ?>
        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $orcidBatchGroup->ID], ['confirm' => __('Are you sure you want to delete # {0}?', $orcidBatchGroup->ID), 'class' => 'side-nav-item']) ?>
        <?= $this->Form->postLink(__('List Users'), ['controller' => 'OrcidUsers', 'action' => 'find', 'prefix' => 'Admin', '?' => ['g' => $orcidBatchGroup->ID]]); ?>
        <?= $this->Form->postLink(__('Expire Cache '), ['action' => 'recache', $orcidBatchGroup->ID]) ?>
    <h3 class="heading"><?= __('Navigation') ?></h3>
        <?= $this->Html->link(__('List Groups'), ['action' => 'index']) ?>
        <?= $this->Html->link(__('Home'), ['controller' => '', 'action' => 'admin', 'prefix' => false]) ?>
</div>