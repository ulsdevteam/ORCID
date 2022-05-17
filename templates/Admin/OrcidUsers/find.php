<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidUser[]|\Cake\Collection\CollectionInterface $orcidUsers
 * @var \App\Model\Entity\OrcidUser $orcidUser
 */
?>
<div class="orcidUsers search content">
	<h2><?= __('Find ORCID Users') ?></h2>
	<p>Find a user by username or ORCID ID.</p>
	<div class="table-responsive">
		<div class="column-responsive column-80">
			<div class="orcidUsers form content">
				<?= $this->Form->create(null,['id' => 'search']) ?>
				<fieldset>
					<?php
						echo $this->Form->select('s', $findTypes, ['div'=> false, 'id' => 's']);
						echo $this->Form->control('q', ['div'=> false, 'label' => false]);
					?>
					<label for='g'>within Group</label>
					<?php
						echo $this->Form->select('g', $batchGroups, ['id' => 's']);
					?>
				</fieldset>
				<?= $this->Form->button(__('Submit')) ?>
				<?= $this->Form->end() ?>
			</div>
		</div>
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
            <tbody>
                <?php foreach ($orcidUsers as $orcidUser): ?>
                <tr>
                    <td><?= $this->Number->format($orcidUser->id) ?></td>
                    <td><?= h($orcidUser->username) ?></td>
                    <td><?= h($orcidUser->orcid) ?></td>
                    <td><?= h($orcidUser->created) ?></td>
                    <td><?= h($orcidUser->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $orcidUser->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $orcidUser->id]) ?>
                        <?= $this->Form->postLink(__('Opt Out'), ['action' => 'optout', $orcidUser->id], ['confirm' => __('Are you sure you want to opt out {0}?', $orcidUser->username)]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $orcidUser->id], ['confirm' => __('Are you sure you want to delete {0}?', $orcidUser->username)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>
<div class="actions">
    <h3 class="heading"><?= __('Actions') ?></h3>
    <?= $this->Html->link(__('New Orcid User'), ['action' => 'add']) ?>
    <h3 class="heading"><?= __('Navigation') ?></h3>
    <?= $this->Html->link(__('List Orcid Users'), ['action' => 'index']) ?>
    <?= $this->Html->link(__('Home'), ['controller' => '', 'action' => 'admin', 'prefix' => false]) ?>
</div>