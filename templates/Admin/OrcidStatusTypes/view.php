<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidStatusType $orcidStatusType
 */
$this->assign('title', 'Workflow Checkpoint');
?>
<aside class="column">
    <nav>
        <div class="navigation actions">
            <h3 class="heading"><?= __('Navigation') ?></h3>
            <?= $this->Html->link(__('List Orcid Status Types'), ['action' => 'index']) ?>
            <?= $this->Html->link(__('Home'), ['controller' => '', 'action' => 'admin', 'prefix' => false]) ?>
        </div>
    </nav>
</aside>
<div class="row">
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
                <h4><?= __('Workflow Checkpoint History') ?></h4>
                <?php if (!empty($orcidStatusType->current_orcid_statuses)) : ?>
                    <div class="table-responsive">
                        <table>
                            <tr>
                                <th><?= __('ORCID User') ?></th>
                                <th><?= __('Status Timestamp') ?></th>
                                <th><?= __('Actions') ?></th>
                            </tr>
                            <?php foreach ($orcidStatusType->current_orcid_statuses as $orcidStatus) : ?>
                                <tr>
                                    <td><?= $orcidStatus->orcid_user->USERNAME ?></td>
                                    <td><?= $orcidStatus->STATUS_TIMESTAMP ?></td>
                                    <td class="actions">
                                        <?= $this->Html->link(__('View'), ['controller' => 'OrcidUsers', 'action' => 'view', $orcidStatus->orcid_user->ID]) ?>
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