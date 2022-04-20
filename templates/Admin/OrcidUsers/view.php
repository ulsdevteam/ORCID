<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidUser $orcidUser
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Orcid User'), ['action' => 'edit', $orcidUser->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Orcid User'), ['action' => 'delete', $orcidUser->id], ['confirm' => __('Are you sure you want to delete # {0}?', $orcidUser->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Orcid Users'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Orcid User'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="orcidUsers view content">
            <h3><?= h($orcidUser->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Username') ?></th>
                    <td><?= h($orcidUser->username) ?></td>
                </tr>
                <tr>
                    <th><?= __('Orcid') ?></th>
                    <td><?= h($orcidUser->orcid) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($orcidUser->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($orcidUser->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($orcidUser->modified) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Orcid Batch Group Caches') ?></h4>
                <?php if (!empty($orcidUser->orcid_batch_group_caches)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Orcid Batch Group Id') ?></th>
                            <th><?= __('Orcid User Id') ?></th>
                            <th><?= __('Deprecated') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($orcidUser->orcid_batch_group_caches as $orcidBatchGroupCaches) : ?>
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
                <h4><?= __('Related Orcid Emails') ?></h4>
                <?php if (!empty($orcidUser->orcid_emails)) : ?>
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
                        <?php foreach ($orcidUser->orcid_emails as $orcidEmails) : ?>
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
            <div class="related">
                <h4><?= __('Related Orcid Statuses') ?></h4>
                <?php if (!empty($orcidUser->orcid_statuses)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Orcid User Id') ?></th>
                            <th><?= __('Orcid Status Type Id') ?></th>
                            <th><?= __('Status Timestamp') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($orcidUser->orcid_statuses as $orcidStatuses) : ?>
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
