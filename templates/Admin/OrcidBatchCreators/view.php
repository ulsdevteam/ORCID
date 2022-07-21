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
                <?= $this->Form->postLink(__('Enable'), ['action' => 'enable', $orcidBatchCreator->ID]) ?>
            <?php else: ?>
                <?= $this->Form->postLink(__('Disable'), ['action' => 'disable', $orcidBatchCreator->ID]) ?>
            <?php endif; ?>
            <?= $this->Html->link(__('List Orcid Batch Creators'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Orcid Batch Creator'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="orcidBatchCreators view content">
            <h3><?= h($orcidBatchCreator->NAME) ?></h3>
            <table>
                <tr>
                    <th><?= __('Username') ?></th>
                    <td><?= h($orcidBatchCreator->NAME) ?></td>
                </tr>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($orcidBatchCreator->DISPLAYNAME) ?></td>
                </tr>
                <tr>
                    <th><?= __('Disabled') ?></th>
                    <td><?= $orcidBatchCreator->FLAGS & $orcidBatchCreator::FLAG_DISABLED ? __("Yes") : __("No") ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
