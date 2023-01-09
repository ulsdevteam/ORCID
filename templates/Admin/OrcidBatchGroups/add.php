<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidBatchGroup $orcidBatchGroup
 */
$this->assign('title', 'Orcid Batch Groups');
?>
<aside class="column">
    <nav>
        <div class="navigation actions">
            <h3 class="heading"><?= __('Navigation') ?></h3>
            <?= $this->Html->link(__('List Groups'), ['action' => 'index']) ?>
            <?= $this->Html->link(__('Home'), ['controller' => '', 'action' => 'admin', 'prefix' => false]) ?>
        </div>
    </nav>
</aside>
<div class="row">
    <div class="column-responsive column-80">
        <div class="orcidBatchGroups form content">
            <?= $this->Form->create($orcidBatchGroup) ?>
            <fieldset>
                <legend><?= __('Add Orcid Batch Group') ?></legend>
                <div>Group members must match the Active Directory Filter (if provided) and must match one of the
                    Central Directory Filters (if provided)</div>
                <?php
                echo $this->Form->control('NAME', ['label' => 'Name']);
                echo $this->Form->control('GROUP_DEFINITION', ['label' => 'Active Directory Filter']);
                echo $this->Form->controls(
                    [
                        'EMPLOYEE_DEFINITION' =>
                        ['label' => 'Employee'],
                        'STUDENT_DEFINITION' =>
                        ['label' => 'Student']
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