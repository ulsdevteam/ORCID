<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidEmail $orcidEmail
 * @var \Cake\Collection\CollectionInterface|string[] $orcidUsers
 * @var \Cake\Collection\CollectionInterface|string[] $orcidBatches
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Orcid Emails'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="orcidEmails form content">
            <?= $this->Form->create($orcidEmail) ?>
            <fieldset>
                <legend><?= __('Add Orcid Email') ?></legend>
                <?php
                    echo $this->Form->control('orcid_user_id', ['options' => $orcidUsers]);
                    echo $this->Form->control('orcid_batch_id', ['options' => $orcidBatches]);
                    echo $this->Form->control('queued', ['empty' => true]);
                    echo $this->Form->control('sent', ['empty' => true]);
                    echo $this->Form->control('cancelled', ['empty' => true]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
