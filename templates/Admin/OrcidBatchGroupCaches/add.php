<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidBatchGroupCache $orcidBatchGroupCache
 * @var \Cake\Collection\CollectionInterface|string[] $orcidBatchGroups
 * @var \Cake\Collection\CollectionInterface|string[] $orcidUsers
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Orcid Batch Group Caches'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="orcidBatchGroupCaches form content">
            <?= $this->Form->create($orcidBatchGroupCache) ?>
            <fieldset>
                <legend><?= __('Add Orcid Batch Group Cache') ?></legend>
                <?php
                    echo $this->Form->control('orcid_batch_group_id', ['options' => $orcidBatchGroups]);
                    echo $this->Form->control('orcid_user_id', ['options' => $orcidUsers]);
                    echo $this->Form->control('deprecated', ['empty' => true]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
