<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidUser $orcidUser
 */
?>
<div class="row">
    <div class="column-responsive column-80">
        <div class="orcidUsers view content">
            <h3><?= h('ORCID User') ?></h3>
            <table>
                <tr>
                    <th><?= __('Username') ?></th>
                    <td><?= h($orcidUser->username) ?></td>
                </tr>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= __("Name needed") ?></td>
                </tr>
                <tr>
                    <th><?= __('ORCID') ?></th>
                    <td><?= $this->Number->format($orcidUser->orcid) ?></td>
                </tr>
                <tr>
                    <th><?= __('Token') ?></th>
                    <td><?= $this->Number->format($orcidUser->token) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($orcidUser->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($orcidUser->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Email') ?></th>
                    <td><?= __("Email needed") ?></td>
                </tr>
                <tr>
                    <th><?= __('Department') ?></th>
                    <td><?= __("Department needed") ?></td>
                </tr>
                <tr>
                    <th><?= __('Current Checkpoint') ?></th>
                    <td><?= h(end($orcidUser->orcid_statuses)->orcid_status_type->name) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Workflow Checkpoint History') ?></h4>
                <?php if (!empty($orcidUser->orcid_statuses)) : ?>
                <?php reset($orcidUser->orcid_statuses)?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Workflow Checkpoint') ?></th>
                            <th><?= __('Timestamp') ?></th>
                        </tr>
                        <?php foreach ($orcidUser->orcid_statuses as $orcidStatuses) : ?>
                        <tr>
                            <td><?= h($orcidStatuses->orcid_status_type->name) ?></td>
                            <td><?= h($orcidStatuses->status_timestamp) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Scheduled Emails') ?></h4>
                <?php if (!empty($orcidUser->orcid_emails)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Orcid Batch Id') ?></th>
                            <th><?= __('Queued') ?></th>
                            <th><?= __('Sent') ?></th>
                            <th><?= __('Cancelled') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($orcidUser->orcid_emails as $orcidEmails) : ?>
                        <tr>
                            <td><?= h($orcidEmails->orcid_batch_id) ?></td>
                            <td><?= h($orcidEmails->queued) ?></td>
                            <td><?= h($orcidEmails->sent) ?></td>
                            <td><?= h($orcidEmails->cancelled) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'OrcidEmails', 'action' => 'view', $orcidEmails->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'OrcidEmails', 'action' => 'edit', $orcidEmails->id]) ?>
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
<div class="actions">
    <h3 class="heading"><?= __('Actions') ?></h3>
    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $orcidUser->id]) ?>
    <?= $this->Form->postLink(__('Opt Out'), ['action' => 'optout', $orcidUser->id], ['confirm' => __('Are you sure you want to opt out {0}?', $orcidUser->username)]) ?>
    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $orcidUser->id], ['confirm' => __('Are you sure you want to delete {0}?', $orcidUser->username)]) ?>
    <h3 class="heading"><?= __('Navigation') ?></h3>
    <?= $this->Html->link(__('List ORCID Users'), ['controller' => 'OrcidUsers', 'action' => 'index', 'prefix' => 'Admin']); ?> 
    <?= $this->Html->link(__('Find Orcid User'), ['action' => 'find']) ?>
    <?= $this->Html->link(__('Home'), ['controller' => '', 'action' => 'admin', 'prefix' => false]) ?>
</div>