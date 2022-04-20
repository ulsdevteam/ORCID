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
                ['action' => 'delete', $orcidUser->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $orcidUser->id), 'class' => 'side-nav-item']
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
                    echo $this->Form->control('username');
                    echo $this->Form->control('orcid');
                    echo $this->Form->control('token');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
