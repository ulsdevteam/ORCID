<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidBatchTrigger $orcidBatchTrigger
 * @var string[]|\Cake\Collection\CollectionInterface $orcidStatusTypes
 * @var string[]|\Cake\Collection\CollectionInterface $orcidBatches
 * @var string[]|\Cake\Collection\CollectionInterface $orcidBatchGroups
 */
?>
<div class="row">
    <div class="column-responsive column-80">
        <div class="orcidBatchTriggers form content">
            <?= $this->Form->create($orcidBatchTrigger) ?>
            <fieldset>
                <legend><?= __('Edit Orcid Batch Trigger') ?></legend>
                <?php
                    echo $this->Form->control('id');
                    echo $this->Form->control('name');
                    echo $this->Form->control('orcid_batch_group_id', ['label' => 'Group', 'options' => $orcidBatchGroups, 'empty' => [0 => '']]);
                    echo $this->Form->control('orcid_status_type_id', ['label' => 'Workflow Checkpoint', 'options' => $orcidStatusTypes]);
                    echo $this->Form->control('orcid_batch_id', ['label' => 'Email Batch', 'options' => $orcidBatches]);
                    echo $this->Form->control('trigger_delay', ['label' => 'Trigger Delay (in days)', 'default' => 0]);
                    echo $this->Form->control('repeat_value', ['label' => 'Repeat Every (in days, 0 for never)', 'default' => 0]);
                    echo $this->Form->control('maximum_repeat', ['label' => 'Repeat Limit (in times, 0 for no limit)', 'default' => 0]);
                    echo $this->Form->control('begin_date');
		            echo $this->Form->control('require_batch_id', ['label' => 'Require Prior Batch', 'options' => $reqBatches]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
<div class="navigation actions">
    <h3 class="heading"><?= __('Actions') ?></h3>
        <?= $this->Html->link(__('View'), ['action' => 'view', $orcidBatchTrigger->id]) ?>\
        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $orcidBatchTrigger->id], ['confirm' => __('Are you sure you want to delete # {0}?', $orcidBatchTrigger->id)]) ?>
    <h3 class="heading"><?= __('Navigation') ?></h3>
        <?= $this->Html->link(__('List Triggers'), ['action' => 'index']) ?>
        <?= $this->Html->link(__('Home'), ['controller' => '', 'action' => 'admin', 'prefix' => false]) ?>
</div>