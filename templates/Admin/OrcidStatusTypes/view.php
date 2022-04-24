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
            <?= $this->Html->link(__('Home'), ['controller' => 'pages', 'action' => 'admin', 'prefix' => false]) ?>
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
                    <th><?= __('Seq') ?></th>
                    <td><?= $orcidStatusType->seq === null ? '' : $this->Number->format($orcidStatusType->seq) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Users at this Checkpoint') ?></h4>
                <?php if (!empty($orcidStatusType->orcid_statuses)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Orcid User') ?></th>
                            <th><?= __('Status Timestamp') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($orcidStatusType->orcid_statuses as $orcidStatuses) : ?>
                            <tr>
                                <td><?= h($orcidStatuses->orcid_user->username) ?></td>
                                <td><?= h($orcidStatuses->status_timestamp) ?></td>
                                <td class="actions">
                                    <?= $this->Html->link(__('View'), ['controller' => 'OrcidUsers', 'action' => 'view', $orcidStatuses->orcid_user->id]) ?>
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
