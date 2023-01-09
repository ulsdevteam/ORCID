<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidBatch $orcidBatch
 * @var string[]|\Cake\Collection\CollectionInterface $orcidBatchCreators
 */
$this->assign('title', 'Edit Batch Email Template');
?>
<aside class="column">
    <nav>
        <div class="navigation actions">
            <h3 class="heading"><?= __('Actions') ?></h3>
            <?= $this->Html->link(__('View'), ['action' => 'view']) ?>
            <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $orcidBatch->ID], ['confirm' => __('Are you sure you want to delete # {0}?', $orcidBatch->ID)]) ?>
            <h3 class="heading"><?= __('Navigation') ?></h3>
            <?= $this->Html->link(__('List Orcid Batches'), ['action' => 'index']) ?>
            <?= $this->Html->link(__('Home'), ['controller' => '', 'action' => 'admin', 'prefix' => false]) ?>
        </div>
    </nav>
</aside>
<?php $this->TinyMCE->editor(['theme' => 'modern', 'selector' => 'textarea', 'plugins' => 'code link']); ?>
<div class="row">
    <div class="column-responsive column-80">
        <div class="orcidBatches form content">
            <?= $this->Form->create($orcidBatch) ?>
            <fieldset>
                <legend><?= __('Edit Batch Email Template') ?></legend>
                <?php
                echo $this->Form->control('NAME', ['label' => 'Name']);
                echo $this->Form->control('FROM_NAME', ['label' => 'From Name']);
                echo $this->Form->control('FROM_ADDR', ['label' => 'From Address']);
                echo $this->Form->control('SUBJECT', ['label' => 'Subject']);
                echo $this->Form->control('BODY', ['label' => 'Body', 'type' => 'textarea', 'required' => false]);
                echo $this->Form->control('REPLY_TO', ['label' => 'Reply To']);
                echo $this->Form->control('ORCID_BATCH_CREATOR_ID', ['label' => 'Template Owner', 'options' => $orcidBatchCreators]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>