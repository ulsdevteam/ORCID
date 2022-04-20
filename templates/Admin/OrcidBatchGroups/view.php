<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidBatchGroup $orcidBatchGroup
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Orcid Batch Group'), ['action' => 'edit', $orcidBatchGroup->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Orcid Batch Group'), ['action' => 'delete', $orcidBatchGroup->id], ['confirm' => __('Are you sure you want to delete # {0}?', $orcidBatchGroup->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Orcid Batch Groups'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Orcid Batch Group'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="orcidBatchGroups view content">
            <h3><?= h($orcidBatchGroup->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($orcidBatchGroup->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Group Definition') ?></th>
                    <td><?= h($orcidBatchGroup->group_definition) ?></td>
                </tr>
                <tr>
                    <th><?= __('Employee Definition') ?></th>
                    <td><?= h($orcidBatchGroup->employee_definition) ?></td>
                </tr>
                <tr>
                    <th><?= __('Student Definition') ?></th>
                    <td><?= h($orcidBatchGroup->student_definition) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($orcidBatchGroup->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Cache Creation Date') ?></th>
                    <td><?= h($orcidBatchGroup->cache_creation_date) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Orcid Batch Group Caches') ?></h4>
                <?php if (!empty($orcidBatchGroup->orcid_batch_group_caches)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Orcid Batch Group Id') ?></th>
                            <th><?= __('Orcid User Id') ?></th>
                            <th><?= __('Deprecated') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($orcidBatchGroup->orcid_batch_group_caches as $orcidBatchGroupCaches) : ?>
                        <tr>
                            <td><?= h($orcidBatchGroupCaches->id) ?></td>
                            <td><?= h($orcidBatchGroupCaches->orcid_batch_group_id) ?></td>
                            <td><?= h($orcidBatchGroupCaches->orcid_user_id) ?></td>
                            <td><?= h($orcidBatchGroupCaches->deprecated) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'OrcidBatchGroupCaches', 'action' => 'view', $orcidBatchGroupCaches->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'OrcidBatchGroupCaches', 'action' => 'edit', $orcidBatchGroupCaches->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'OrcidBatchGroupCaches', 'action' => 'delete', $orcidBatchGroupCaches->id], ['confirm' => __('Are you sure you want to delete # {0}?', $orcidBatchGroupCaches->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Orcid Batch Triggers') ?></h4>
                <?php if (!empty($orcidBatchGroup->orcid_batch_triggers)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Name') ?></th>
                            <th><?= __('Orcid Status Type Id') ?></th>
                            <th><?= __('Orcid Batch Id') ?></th>
                            <th><?= __('Trigger Delay') ?></th>
                            <th><?= __('Orcid Batch Group Id') ?></th>
                            <th><?= __('Begin Date') ?></th>
                            <th><?= __('Require Batch Id') ?></th>
                            <th><?= __('Repeat') ?></th>
                            <th><?= __('Maximum Repeat') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($orcidBatchGroup->orcid_batch_triggers as $orcidBatchTriggers) : ?>
                        <tr>
                            <td><?= h($orcidBatchTriggers->id) ?></td>
                            <td><?= h($orcidBatchTriggers->name) ?></td>
                            <td><?= h($orcidBatchTriggers->orcid_status_type_id) ?></td>
                            <td><?= h($orcidBatchTriggers->orcid_batch_id) ?></td>
                            <td><?= h($orcidBatchTriggers->trigger_delay) ?></td>
                            <td><?= h($orcidBatchTriggers->orcid_batch_group_id) ?></td>
                            <td><?= h($orcidBatchTriggers->begin_date) ?></td>
                            <td><?= h($orcidBatchTriggers->require_batch_id) ?></td>
                            <td><?= h($orcidBatchTriggers->repeat) ?></td>
                            <td><?= h($orcidBatchTriggers->maximum_repeat) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'OrcidBatchTriggers', 'action' => 'view', $orcidBatchTriggers->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'OrcidBatchTriggers', 'action' => 'edit', $orcidBatchTriggers->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'OrcidBatchTriggers', 'action' => 'delete', $orcidBatchTriggers->id], ['confirm' => __('Are you sure you want to delete # {0}?', $orcidBatchTriggers->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
