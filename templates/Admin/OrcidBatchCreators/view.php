<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidBatchCreator $orcidBatchCreator
 */
$this->assign('title', 'Administrator');
?>
<aside class="column">
    <nav>
        <div class="navigation actions">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?php if ($orcidBatchCreator->FLAGS & $orcidBatchCreator::FLAG_DISABLED) : ?>
                <?= $this->Form->postLink(__('Enable'), ['action' => 'enable', $orcidBatchCreator->ID]) ?>
            <?php else : ?>
                <?= $this->Form->postLink(__('Disable'), ['action' => 'disable', $orcidBatchCreator->ID]) ?>
            <?php endif; ?>
            <h3 class="heading"><?= __('Navigation') ?></h3>
            <?= $this->Html->link(__('List Orcid Batch Creators'), ['action' => 'index']) ?>
            <?= $this->Html->link(__('Home'), ['controller' => '', 'action' => 'admin', 'prefix' => false]) ?>
        </div>
    </nav>
</aside>
<div class="row">
    <div class="column-responsive column-80">
        <div class="orcidBatchCreators view content">
            <h3><?= __($this->fetch('title')) ?></h3>
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