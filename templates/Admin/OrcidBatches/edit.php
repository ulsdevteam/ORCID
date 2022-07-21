<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidBatch $orcidBatch
 * @var string[]|\Cake\Collection\CollectionInterface $orcidBatchCreators
 */
?>
<?php $this->TinyMCE->editor(['theme' => 'modern', 'selector' => 'textarea', 'plugins' => 'code link']); ?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('View'), ['action' => 'view', $orcidBatch->ID]) ?>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $orcidBatch->ID],
                ['confirm' => __('Are you sure you want to delete # {0}?', $orcidBatch->ID), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Orcid Batches'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="orcidBatches form content">
            <?= $this->Form->create($orcidBatch) ?>
            <fieldset>
                <legend><?= __('Edit Orcid Batch') ?></legend>
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
