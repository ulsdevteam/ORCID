<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidBatchCreator $orcidBatchCreator
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?php if ($orcidBatchCreator->flags & $orcidBatchCreator::FLAG_DISABLED): ?>
                <?= $this->Form->postLink(__('Enable'), ['action' => 'enable', $orcidBatchCreator->id]) ?>
            <?php else: ?>
                <?= $this->Form->postLink(__('Disable'), ['action' => 'disable', $orcidBatchCreator->id]) ?>
            <?php endif; ?>
            <?= $this->Html->link(__('List Orcid Batch Creators'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Orcid Batch Creator'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="orcidBatchCreators view content">
            <h3><?= h($orcidBatchCreator->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Username') ?></th>
                    <td><?= h($orcidBatchCreator->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td></td>
                </tr>
                <tr>
                    <th><?= __('Disabled') ?></th>
                    <td><?= $orcidBatchCreator->flags & $orcidBatchCreator::FLAG_DISABLED ? __("Yes") : __("No") ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Orcid Batches') ?></h4>
                <?php if (!empty($orcidBatchCreator->orcid_batches)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Name') ?></th>
                            <th><?= __('Subject') ?></th>
                            <th><?= __('Body') ?></th>
                            <th><?= __('From Name') ?></th>
                            <th><?= __('From Addr') ?></th>
                            <th><?= __('Reply To') ?></th>
                            <th><?= __('Orcid Batch Creator Id') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($orcidBatchCreator->orcid_batches as $orcidBatches) : ?>
                        <tr>
                            <td><?= h($orcidBatches->id) ?></td>
                            <td><?= h($orcidBatches->name) ?></td>
                            <td><?= h($orcidBatches->subject) ?></td>
                            <td><?= h($orcidBatches->body) ?></td>
                            <td><?= h($orcidBatches->from_name) ?></td>
                            <td><?= h($orcidBatches->from_addr) ?></td>
                            <td><?= h($orcidBatches->reply_to) ?></td>
                            <td><?= h($orcidBatches->orcid_batch_creator_id) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'OrcidBatches', 'action' => 'view', $orcidBatches->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'OrcidBatches', 'action' => 'edit', $orcidBatches->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'OrcidBatches', 'action' => 'delete', $orcidBatches->id], ['confirm' => __('Are you sure you want to delete # {0}?', $orcidBatches->id)]) ?>
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
