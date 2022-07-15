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
            <?= $this->Html->link(__('Edit Orcid Batch'), ['action' => 'edit', $orcidBatch->ID], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Orcid Batch'), ['action' => 'delete', $orcidBatch->ID], ['confirm' => __('Are you sure you want to delete # {0}?', $orcidBatch->ID), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Orcid Batches'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Orcid Batch'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="orcidBatches view content">
            <h3><?= h($orcidBatch->NAME) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($orcidBatch->NAME) ?></td>
                </tr>
                <tr>
                    <th><?= __('Creator') ?></th>
                    <td><?= $orcidBatch->has('orcid_batch_creator') ? $this->Html->link($orcidBatch->orcid_batch_creator->NAME, ['controller' => 'OrcidBatchCreators', 'action' => 'view', $orcidBatch->orcid_batch_creator->ID]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('From Name') ?></th>
                    <td><?= h($orcidBatch->FROM_NAME) ?></td>
                </tr>
                <tr>
                    <th><?= __('Reply To') ?></th>
                    <td><?= h($orcidBatch->REPLY_TO) ?></td>
                </tr>
                <tr>
                    <th><?= __('Subject') ?></th>
                    <td><?= h($orcidBatch->SUBJECT) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Body') ?></strong>
                <iframe class="emailpreview" src="../preview/<?php echo $orcidBatch->ID; ?>">
			    </iframe>
            </div>
            <div class="preview">
                <strong><?= __('Preview') ?></strong>
                <?php
                echo $this->Form->create($orcidBatch, ['url' => ['action' => 'preview', $orcidBatch->ID]]);
                echo $this->Form->control('recipient');
                echo $this->Form->button(__('Preview'));
                echo $this->Form->end();
                ?>
            </div>
            
            <div class="related">
                <h4><?= __('Triggers Attached to this Template') ?></h4>
                <?= $this->Html->link(__('New Trigger'), ['action' => 'add'], ['class' => 'button']) ?>
                <?php if (!empty($orcidBatch->orcid_batch_triggers)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Name') ?></th>
                            <th><?= __('Workflow Checkpoint') ?></th>
                            <th><?= __('Trigger Delay') ?></th>
                            <th><?= __('Group') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($orcidBatch->orcid_batch_triggers as $orcidBatchTriggers) : ?>
                        <tr>
                            <td><?= h($orcidBatchTriggers->NAME) ?></td>
                            <td><?= h($orcidBatchTriggers->ORCID_STATUS_TYPE_ID) ?></td>
                            <td><?= h($orcidBatchTriggers->TRIGGER_DELAY) ?></td>
                            <td><?= h($orcidBatchTriggers->ORCID_BATCH_GROUP_ID) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'OrcidBatchTriggers', 'action' => 'view', $orcidBatchTriggers->ID]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'OrcidBatchTriggers', 'action' => 'edit', $orcidBatchTriggers->ID]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'OrcidBatchTriggers', 'action' => 'delete', $orcidBatchTriggers->id], ['confirm' => __('Are you sure you want to delete # {0}?', $orcidBatchTriggers->ID)]) ?>
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
