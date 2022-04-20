<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidStatusType $orcidStatusType
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Orcid Status Type'), ['action' => 'edit', $orcidStatusType->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Orcid Status Type'), ['action' => 'delete', $orcidStatusType->id], ['confirm' => __('Are you sure you want to delete # {0}?', $orcidStatusType->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Orcid Status Types'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Orcid Status Type'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="orcidStatusTypes view content">
            <h3><?= h($orcidStatusType->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($orcidStatusType->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($orcidStatusType->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Seq') ?></th>
                    <td><?= $orcidStatusType->seq === null ? '' : $this->Number->format($orcidStatusType->seq) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Orcid Batch Triggers') ?></h4>
                <?php if (!empty($orcidStatusType->orcid_batch_triggers)) : ?>
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
                        <?php foreach ($orcidStatusType->orcid_batch_triggers as $orcidBatchTriggers) : ?>
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
            <div class="related">
                <h4><?= __('Related Orcid Statuses') ?></h4>
                <?php if (!empty($orcidStatusType->orcid_statuses)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Orcid User Id') ?></th>
                            <th><?= __('Orcid Status Type Id') ?></th>
                            <th><?= __('Status Timestamp') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($orcidStatusType->orcid_statuses as $orcidStatuses) : ?>
                        <tr>
                            <td><?= h($orcidStatuses->id) ?></td>
                            <td><?= h($orcidStatuses->orcid_user_id) ?></td>
                            <td><?= h($orcidStatuses->orcid_status_type_id) ?></td>
                            <td><?= h($orcidStatuses->status_timestamp) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'OrcidStatuses', 'action' => 'view', $orcidStatuses->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'OrcidStatuses', 'action' => 'edit', $orcidStatuses->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'OrcidStatuses', 'action' => 'delete', $orcidStatuses->id], ['confirm' => __('Are you sure you want to delete # {0}?', $orcidStatuses->id)]) ?>
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
