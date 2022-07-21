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
                    <td><?= h($orcidUser->USERNAME) ?></td>
                </tr>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($orcidUser->displayname) ?></td>
                </tr>
                <tr>
                    <th><?= __('ORCID') ?></th>
                    <?php if (!empty($orcidUser->ORCID)): ?>
                        <td><?= $orcidUser->ORCID ?></td>
                    <?php else: ?>
                        <td></td>
                    <?php endif; ?>
                </tr>
                <tr>
                    <th><?= __('Token') ?></th>
                    <?php if (!empty($orcidUser->TOKEN)): ?>
                        <td><?= $orcidUser->TOKEN ?></td>
                    <?php else: ?>
                        <td></td>
                    <?php endif; ?>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($orcidUser->CREATED) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($orcidUser->MODIFIED) ?></td>
                </tr>
                <tr>
                    <th><?= __('Email') ?></th>
                    <td><?= h($orcidUser->email) ?></td>
                </tr>
                <tr>
                    <th><?= __('Department') ?></th>
                    <td><?= h($orcidUser->rcdepartment) ?></td>
                </tr>
                <tr>
                    <th><?= __('Current Checkpoint') ?></th>
                    <td><?= h(isset($orcidUser->current_orcid_statuses[0]) ? $orcidUser->current_orcid_statuses[0]->orcid_status_type->NAME : '') ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Workflow Checkpoint History') ?></h4>
                <?php if (!empty($orcidUser->all_orcid_statuses)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Workflow Checkpoint') ?></th>
                            <th><?= __('Timestamp') ?></th>
                        </tr>
                        <?php foreach ($orcidUser->all_orcid_statuses as $orcidStatus) : ?>
                            <tr>
                                <td><?= h($orcidStatus->orcid_status_type->NAME) ?></td>
                                <td><?= h($orcidStatus->STATUS_TIMESTAMP) ?></td>
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
                            <td><?= h($orcidEmails->ORCID_BATCH_ID) ?></td>
                            <td><?= h($orcidEmails->QUEUED) ?></td>
                            <td><?= h($orcidEmails->SENT) ?></td>
                            <td><?= h($orcidEmails->CANCELLED) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'OrcidEmails', 'action' => 'view', $orcidEmails->ID]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'OrcidEmails', 'action' => 'edit', $orcidEmails->ID]) ?>
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
<div class="navigation actions">
    <h3 class="heading"><?= __('Actions') ?></h3>
    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $orcidUser->ID]) ?>
    <?= $this->Form->postLink(__('Opt Out'), ['action' => 'optout', $orcidUser->ID], ['confirm' => __('Are you sure you want to opt out {0}?', $orcidUser->USERNAME)]) ?>
    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $orcidUser->ID], ['confirm' => __('Are you sure you want to delete {0}?', $orcidUser->USERNAME)]) ?>
    <h3 class="heading"><?= __('Navigation') ?></h3>
    <?= $this->Html->link(__('List ORCID Users'), ['controller' => 'OrcidUsers', 'action' => 'index', 'prefix' => 'Admin']); ?> 
    <?= $this->Html->link(__('Find Orcid User'), ['action' => 'find']) ?>
    <?= $this->Html->link(__('Home'), ['controller' => '', 'action' => 'admin', 'prefix' => false]) ?>
</div>