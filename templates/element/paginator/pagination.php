<?php

/**
 * @var \App\View\AppView $this
 */
?>
<div class="paginator">
	<ul class="pagination">
		<?= $this->Paginator->first('<< ' . __('first')) ?>
		<?= $this->Paginator->prev('< ' . __('previous')) ?>
		<?= $this->Paginator->numbers() ?>
		<?= $this->Paginator->next(__('next') . ' >') ?>
		<?= $this->Paginator->last(__('last') . ' >>') ?>
	</ul>
	<?= $this->Paginator->limitControl([20 => 20, 25 => 25, 50 => 50, 100 => 100], null, [
		'templates' => [
			'inputContainer' => (isset($selectedType) ? '<input type="hidden" name="f" value="{{s}}"/>' : '') .
			(isset($selectedGroup) ? '<input type="hidden" name="g" value="{{g}}"/>' : '') .
			(isset($userQuery) ? '<input type="hidden" name="q" value="{{q}}"/>' : '') .
			(isset($selectedStatus) ? '<input type="hidden" name="s" value="{{s}}"/>' : '') .
			$this->Paginator->Form->getTemplates('inputContainer')],
		'templateVars' => [
			'f' => isset($selectedType) ? $selectedType : '',
			'g' => isset($selectedGroup) ? $selectedGroup : '',
			'q' => isset($userQuery) ? $userQuery : '',
			's' => isset($selectedStatus) ? $selectedStatus : '',
			]
		]); ?>
	<p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?>
	</p>
</div>