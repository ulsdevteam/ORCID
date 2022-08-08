<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidUser $orcidUser
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $orcidUser->ID],
                ['confirm' => __('Are you sure you want to delete # {0}?', $orcidUser->ID), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Orcid Users'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="orcidUsers form content">
            <?= $this->Form->create($orcidUser) ?>
            <fieldset>
                <legend><?= __('Edit Orcid User') ?></legend>
                <?php
                echo $this->Form->control('USERNAME');
                echo $this->Form->control('ORCID');
                echo $this->Form->control('TOKEN');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>