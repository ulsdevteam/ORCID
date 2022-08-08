<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidBatchGroupCache $orcidBatchGroupCache
 * @var string[]|\Cake\Collection\CollectionInterface $orcidBatchGroups
 * @var string[]|\Cake\Collection\CollectionInterface $orcidUsers
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $orcidBatchGroupCache->ID],
                ['confirm' => __('Are you sure you want to delete # {0}?', $orcidBatchGroupCache->ID), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Orcid Batch Group Caches'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="orcidBatchGroupCaches form content">
            <?= $this->Form->create($orcidBatchGroupCache) ?>
            <fieldset>
                <legend><?= __('Edit Orcid Batch Group Cache') ?></legend>
                <?php
                echo $this->Form->control('ORCID_BATCH_GROUP_ID', ['options' => $orcidBatchGroups]);
                echo $this->Form->control('ORCID_USER_ID', ['options' => $orcidUsers]);
                echo $this->Form->control('DEPRECATED', ['empty' => true]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>