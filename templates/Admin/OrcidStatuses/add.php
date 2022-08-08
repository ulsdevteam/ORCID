<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidStatus $orcidStatus
 * @var \Cake\Collection\CollectionInterface|string[] $orcidUsers
 * @var \Cake\Collection\CollectionInterface|string[] $orcidStatusTypes
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Orcid Statuses'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="orcidStatuses form content">
            <?= $this->Form->create($orcidStatus) ?>
            <fieldset>
                <legend><?= __('Add Orcid Status') ?></legend>
                <?php
                echo $this->Form->control('orcid_user_id', ['options' => $orcidUsers]);
                echo $this->Form->control('orcid_status_type_id', ['options' => $orcidStatusTypes]);
                echo $this->Form->control('status_timestamp');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>