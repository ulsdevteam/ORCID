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
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="orcidStatusTypes form content">
            <?= $this->Form->create($orcidStatusType) ?>
            <fieldset>
                <legend><?= __('Add Orcid Status Type') ?></legend>
                <?php
                echo $this->Form->control('name');
                echo $this->Form->control('seq');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>