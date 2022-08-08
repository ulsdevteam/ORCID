<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidBatchGroup $orcidBatchGroup
 */
?>
<div class="row">
    <div class="column-responsive column-80">
        <div class="orcidBatchGroups form content">
            <?= $this->Form->create($orcidBatchGroup) ?>
            <fieldset>
                <legend><?= __('Edit Orcid Batch Group') ?></legend>
                <div>Group members must match the Active Directory Filter (if provided) and must match one of the
                    Central Directory Filters (if provided)</div>
                <?php
                echo $this->Form->control('name');
                echo $this->Form->control('group_definition');
                echo $this->Form->controls(
                    [
                        'student_definition' =>
                        ['label' => 'Student'],
                        'employee_definition' =>
                        ['label' => 'Employee']
                    ],
                    ['legend' => 'Central Directory Filters']
                );
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
<aside class="column">
    <nav>
        <div class="navigation actions">
            <h3 class="heading"><?= __('Actions') ?></h3>
            <?= $this->Html->link(__('View'), ['action' => 'view', $orcidBatchGroup->ID]) ?>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $orcidBatchGroup->ID],
                ['confirm' => __('Are you sure you want to delete # {0}?', $orcidBatchGroup->ID), 'class' => 'side-nav-item']
            ) ?>
            <h3 class="heading"><?= __('Navigation') ?></h3>
            <?= $this->Html->link(__('List Groups'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('Home'), ['controller' => '', 'action' => 'admin', 'prefix' => false]) ?>
        </div>
    </nav>
</aside>