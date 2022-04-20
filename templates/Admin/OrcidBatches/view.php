<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidBatch $orcidBatch
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Orcid Batch'), ['action' => 'edit', $orcidBatch->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Orcid Batch'), ['action' => 'delete', $orcidBatch->id], ['confirm' => __('Are you sure you want to delete # {0}?', $orcidBatch->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Orcid Batches'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Orcid Batch'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="orcidBatches view content">
            <h3><?= h($orcidBatch->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($orcidBatch->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Subject') ?></th>
                    <td><?= h($orcidBatch->subject) ?></td>
                </tr>
                <tr>
                    <th><?= __('From Name') ?></th>
                    <td><?= h($orcidBatch->from_name) ?></td>
                </tr>
                <tr>
                    <th><?= __('From Addr') ?></th>
                    <td><?= h($orcidBatch->from_addr) ?></td>
                </tr>
                <tr>
                    <th><?= __('Reply To') ?></th>
                    <td><?= h($orcidBatch->reply_to) ?></td>
                </tr>
                <tr>
                    <th><?= __('Orcid Batch Creator') ?></th>
                    <td><?= $orcidBatch->has('orcid_batch_creator') ? $this->Html->link($orcidBatch->orcid_batch_creator->name, ['controller' => 'OrcidBatchCreators', 'action' => 'view', $orcidBatch->orcid_batch_creator->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($orcidBatch->id) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Body') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($orcidBatch->body)); ?>
                </blockquote>
            </div>
            <div class="related">
                <h4><?= __('Related Orcid Batch Triggers') ?></h4>
                <?php if (!empty($orcidBatch->orcid_batch_triggers)) : ?>
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
                        <?php foreach ($orcidBatch->orcid_batch_triggers as $orcidBatchTriggers) : ?>
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
                <h4><?= __('Related Orcid Emails') ?></h4>
                <?php if (!empty($orcidBatch->orcid_emails)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Orcid User Id') ?></th>
                            <th><?= __('Orcid Batch Id') ?></th>
                            <th><?= __('Queued') ?></th>
                            <th><?= __('Sent') ?></th>
                            <th><?= __('Cancelled') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($orcidBatch->orcid_emails as $orcidEmails) : ?>
                        <tr>
                            <td><?= h($orcidEmails->id) ?></td>
                            <td><?= h($orcidEmails->orcid_user_id) ?></td>
                            <td><?= h($orcidEmails->orcid_batch_id) ?></td>
                            <td><?= h($orcidEmails->queued) ?></td>
                            <td><?= h($orcidEmails->sent) ?></td>
                            <td><?= h($orcidEmails->cancelled) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'OrcidEmails', 'action' => 'view', $orcidEmails->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'OrcidEmails', 'action' => 'edit', $orcidEmails->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'OrcidEmails', 'action' => 'delete', $orcidEmails->id], ['confirm' => __('Are you sure you want to delete # {0}?', $orcidEmails->id)]) ?>
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
