<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidUser $orcidUser
 */
$this->assign('title', 'Edit ORCID User');
?>
<aside class="column">
    <nav>
        <div class="navigation actions">
            <h3 class="heading"><?= __('Actions') ?></h3>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $orcidUser->ID],
                ['confirm' => __('Are you sure you want to delete # {0}?', $orcidUser->ID), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Orcid Users'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </nav>
</aside>
<div class="row">
    <div class="column-responsive column-80">
        <div class="orcidUsers form content">
            <?= $this->Form->create($orcidUser) ?>
            <fieldset>
                <legend><?= __('Edit Orcid User') ?></legend>
                <?php
                echo $this->Form->control('USERNAME', ['label' => 'Username']);
                echo $this->Form->control('ORCID', ['label' => 'ORCID']);
                echo $this->Form->control('TOKEN', ['label' => 'Token']);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>