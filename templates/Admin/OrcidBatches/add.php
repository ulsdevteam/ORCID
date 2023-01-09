<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidBatch $orcidBatch
 * @var \Cake\Collection\CollectionInterface|string[] $orcidBatchCreators
 */
$this->assign('title', 'Add Batch Email Template');
?>
<?php $this->TinyMCE->editor(['theme' => 'modern', 'selector' => 'textarea', 'plugins' => 'code link']); ?>
<aside class="column">
    <nav>
        <div class="navigation actions">
            <h3 class="heading"><?= __('Navigation') ?></h3>
            <?= $this->Html->link(__('List Orcid Batches'), ['action' => 'index']) ?>
            <?= $this->Html->link(__('Home'), ['controller' => '', 'action' => 'admin', 'prefix' => false]) ?>
        </div>
    </nav>
</aside>
<div class="row">
    <div class="column-responsive column-80">
        <div class="orcidBatches form content">
            <?= $this->Form->create($orcidBatch) ?>
            <fieldset>
                <legend><?= __('Add Orcid Batch') ?></legend>
                <?php
                echo $this->Form->control('NAME', ['label' => 'Email Description']);
                echo $this->Form->control('FROM_NAME', ['label' => 'From Name']);
                echo $this->Form->control('FROM_ADDR', ['label' => 'From Address']);
                echo $this->Form->control('SUBJECT', ['label' => 'Subject']);
                echo $this->Form->control('BODY', ['label' => 'Body', 'type' => 'textarea', 'required' => false]);
                echo $this->Form->control('REPLY_TO', ['label' => 'Reply To']);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>