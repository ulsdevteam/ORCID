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
            <?= $this->Html->link(__('List Orcid Status Types'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('Home'), ['controller' => '', 'action' => 'admin', 'prefix' => false]) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="orcidStatusTypes view content">
            <h3><?= h($orcidStatusType->NAME) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($orcidStatusType->NAME) ?></td>
                </tr>
                <tr>
                    <th><?= __('Seq') ?></th>
                    <td><?= $orcidStatusType->SEQ === null ? '' : $this->Number->format($orcidStatusType->SEQ) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Users at this Checkpoint') ?></h4>
                <?php if (!empty($orcidStatusType->current_orcid_statuses)) : ?>
                    <div class="table-responsive">
                        <table>
                            <tr>
                                <th><?= __('ORCID User') ?></th>
                                <th><?= __('Status Timestamp') ?></th>
                                <th class="actions"><?= __('Actions') ?></th>
                            </tr>
                            <?php foreach ($orcidStatusType->current_orcid_statuses as $currentStatus) : ?>
                                <tr>
                                    <td><?= h($currentStatus->orcid_user->USERNAME) ?></td>
                                    <td><?= h($currentStatus->STATUS_TIMESTAMP) ?></td>
                                    <td class="actions">
                                        <?= $this->Html->link(__('View'), ['controller' => 'OrcidUsers', 'action' => 'view', $currentStatus->orcid_user->ID]) ?>
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