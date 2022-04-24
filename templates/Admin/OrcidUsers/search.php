<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidUser[]|\Cake\Collection\CollectionInterface $orcidUsers
 */
?>
<div class="orcidUsers search content">
	<h3><?= __('Find ORCID Users') ?></h3>
	<p>Find a user by username or ORCID ID.</p>
	<div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('username') ?></th>
                    <th><?= $this->Paginator->sort('orcid') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th
                </tr>
            </thead>
	<tr>
	</tr>
	<tr>
			<th><?php echo $this->Paginator->sort('username'); ?></th>
			<th><?php echo $this->Paginator->sort('orcid', 'ORCID'); ?></th>
			<th><?php echo __('Name'); ?></th>
			<th><?php echo __('RC'); ?></th>
			<th><?php echo __('Department'); ?></th>
			<th><?php echo $this->Paginator->sort('CurrentOrcidStatus.orcid_status_type_id', 'Current Checkpoint'); ?></th>
			<th><?php echo $this->Paginator->sort('CurrentOrcidStatus.status_timestamp', 'As Of'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($orcidUsers as $orcidUser): ?>
	<tr>
		<td><?php echo h($orcidUser['OrcidUser']['username']); ?>&nbsp;</td>
		<td><?php echo h($orcidUser['OrcidUser']['orcid']); ?>&nbsp;</td>
		<td><?php echo h($orcidUser['Person']['displayname']); ?>&nbsp;</td>
		<td><?php echo h($orcidUser['Person']['pittemployeerc']); ?>&nbsp;</td>
		<td><?php echo h($orcidUser['Person']['department']); ?>&nbsp;</td>
		<td><?php echo h($orcidStatusTypes[$orcidUser['CurrentOrcidStatus']['orcid_status_type_id']]); ?>&nbsp;</td>
		<td><?php echo h($orcidUser['CurrentOrcidStatus']['status_timestamp']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $orcidUser['OrcidUser']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $orcidUser['OrcidUser']['id'])); ?>
			<?php echo $this->Form->postLink(__('Opt Out'), array('action' => 'optout', $orcidUser['OrcidUser']['id']), array(), __('Are you sure you want to opt out %s?', $orcidUser['OrcidUser']['username'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $orcidUser['OrcidUser']['id']), array(), __('Are you sure you want to delete %s?', $orcidUser['OrcidUser']['username'])); ?>
		</td>
	</tr>
	<?php endforeach; ?>
	</tbody>
	</table>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	<div class="actions">
		<?php if ($orcidUsers) { echo $this->Html->link(__('Download CSV'), array('action' => 'report', '?' => $this->request->query)); } ?>
	</div>
	</div>
</div>
</div>
<div class="actions">
    <h3 class="heading"><?= __('Actions') ?></h3>
    <?= $this->Html->link(__('New Orcid User'), ['action' => 'add']) ?>
    <h3 class="heading"><?= __('Navigation') ?></h3>
    <?= $this->Html->link(__('List Orcid Users'), ['action' => 'index']) ?>
    <?= $this->Html->link(__('Home'), ['controller' => '', 'action' => 'admin', 'prefix' => false]) ?>
</div>