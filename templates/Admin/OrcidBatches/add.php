<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidBatch $orcidBatch
 * @var \Cake\Collecytion\CollectionInterface|string[] $orcidBatchCreators
 */
?>
<?php $this->TinyMCE->editor(['theme' => 'modern', 'selector' => 'textarea', 'plugins' => 'code link']); ?>
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
                    echo $this->Form->control('from_name');
                    echo $this->Form->control('from_addr', ['label' => 'From Address']);
                    echo $this->Form->control('subject');
                    echo $this->Form->control('body', ['type' => 'textarea', 'required' => false]);
                    echo $this->Form->control('reply_to');
                    echo $this->Form->control('orcid_batch_creator_id', ['label' => 'Template Owner', 'options' => $orcidBatchCreators]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
