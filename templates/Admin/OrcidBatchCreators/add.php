<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidBatchCreator $orcidBatchCreator
 */
$this->assign('title', 'Add Administrator');
?>
<aside class="column">
    <nav>
        <div class="navigation actions">
            <h4 class="heading"><?= __('Navigation') ?></h4>
            <?= $this->Html->link(__('List Orcid Batch Creators'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('Home'), ['controller' => '', 'action' => 'admin', 'prefix' => false]) ?>
        </div>
    </nav>
</aside>
<div class="row">
    <div class="column-responsive column-80">
        <div class="orcidBatchCreators form content">
            <h3><?= __('Add Administrator') ?></h3>
            <?= $this->Form->create($orcidBatchCreator) ?>
            <fieldset>
                <p><?= __('Enter the University Computer Account name to create an new administrative user.') ?></p>
                <?php
                echo $this->Form->control('NAME', ['label' => 'Username']);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>