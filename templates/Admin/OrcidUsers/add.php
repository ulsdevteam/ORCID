<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidUser $orcidUser
 */
?>
<div class="row">
    <div class="column-responsive column-80">
        <div class="orcidUsers form content">
            <?= $this->Form->create($orcidUser) ?>
            <fieldset>
                <legend><?= __('Add Orcid User') ?></legend>
                <?php
                    echo $this->Form->control('USERNAME');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
<div class="navigation actions">
    <h3 class="heading"><?= __('Navigation') ?></h3>
    <?= $this->Html->link(__('List Orcid Users'), ['action' => 'index']) ?>
    <?= $this->Html->link(__('Find Orcid User'), ['action' => 'find']) ?>
    <?= $this->Html->link(__('Home'), ['controller' => '', 'action' => 'admin', 'prefix' => false]) ?>
</div>