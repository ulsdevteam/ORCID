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
                <legend><?= __('Add Orcid Batch Group') ?></legend>
                <div>Group members must match the Active Directory Filter (if provided) and must match one of the Central Directory Filters (if provided)</div>
	            <?php
                    echo $this->Form->control('name');
                    echo $this->Form->control('group_definition', ['label' => 'Active Directory Filter']);
                ?>
                <?php
                    echo $this->Form->controls(['employee_definition' => 
                                                    ['label' => 'Student'], 
                                                'student_definition' => 
                                                    ['label' => 'Employee']], 
                                            ['legend' => 'Central Directory Filters']);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
<div class="navigation actions">
    <h3 class="heading"><?= __('Navigation') ?></h3>
    <?= $this->Html->link(__('List Groups'), ['action' => 'index']) ?>
    <?= $this->Html->link(__('Home'), ['controller' => '', 'action' => 'admin', 'prefix' => false]) ?>
</div>