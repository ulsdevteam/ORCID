<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidBatchTrigger $orcidBatchTrigger
 * @var \Cake\Collection\CollectionInterface|string[] $orcidStatusTypes
 * @var \Cake\Collection\CollectionInterface|string[] $orcidBatches
 * @var \Cake\Collection\CollectionInterface|string[] $orcidBatchGroups
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Orcid Batch Triggers'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="orcidBatchTriggers form content">
            <?= $this->Form->create($orcidBatchTrigger) ?>
            <fieldset>
                <legend><?= __('Add Orcid Batch Trigger') ?></legend>
                <?php
                    echo $this->Form->control('name');
                    echo $this->Form->control('orcid_status_type_id', ['options' => $orcidStatusTypes]);
                    echo $this->Form->control('orcid_batch_id', ['options' => $orcidBatches]);
                    echo $this->Form->control('trigger_delay');
                    echo $this->Form->control('orcid_batch_group_id', ['options' => $orcidBatchGroups, 'empty' => true]);
                    echo $this->Form->control('begin_date', ['empty' => true]);
                    echo $this->Form->control('repeat_value');
                    echo $this->Form->control('maximum_repeat');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
