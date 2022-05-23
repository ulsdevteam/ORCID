<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidBatch $orcidBatch
 * @var \Cake\Collection\CollectionInterface|string[] $orcidBatchCreators
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Orcid Batches'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="orcidBatches form content">
            <?= $this->Form->create($orcidBatch) ?>
            <fieldset>
                <legend><?= __('Add Orcid Batch') ?></legend>
                <?php
                    echo $this->Form->control('name');
                    echo $this->Form->control('subject');
                    echo $this->Form->control('body', ['type' => 'textarea']);
                    echo $this->Form->control('from_name');
                    echo $this->Form->control('from_addr');
                    echo $this->Form->control('reply_to');
                    echo $this->Form->control('orcid_batch_creator_id', ['options' => $orcidBatchCreators]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
