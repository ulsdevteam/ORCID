<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidBatchCreator $orcidBatchCreator
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $orcidBatchCreator->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $orcidBatchCreator->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Orcid Batch Creators'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="orcidBatchCreators form content">
            <?= $this->Form->create($orcidBatchCreator) ?>
            <fieldset>
                <legend><?= __('Edit Orcid Batch Creator') ?></legend>
                <?php
                    echo $this->Form->control('name');
                    echo $this->Form->control('flags');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
