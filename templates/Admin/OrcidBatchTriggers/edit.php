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
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $orcidBatchTrigger->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $orcidBatchTrigger->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Orcid Batch Triggers'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="orcidBatchTriggers form content">
            <?= $this->Form->create($orcidBatchTrigger) ?>
            <fieldset>
                <legend><?= __('Edit Orcid Batch Trigger') ?></legend>
                <?php
                    echo $this->Form->control('name');
                    echo $this->Form->control('orcid_status_type_id', ['options' => $orcidStatusTypes]);
                    echo $this->Form->control('orcid_batch_id', ['options' => $orcidBatches]);
                    echo $this->Form->control('trigger_delay');
                    echo $this->Form->control('orcid_batch_group_id', ['options' => $orcidBatchGroups, 'empty' => true]);
                    echo $this->Form->control('begin_date', ['empty' => true]);
                    echo $this->Form->control('repeat');
                    echo $this->Form->control('maximum_repeat');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
