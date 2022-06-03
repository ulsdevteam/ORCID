<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidBatchCreator[]|\Cake\Collection\CollectionInterface $orcidBatchCreators
 */
?>
<div class="orcidBatchCreators index content">
    <?= $this->Html->link(__('New Orcid Batch Creator'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Orcid Batch Creators') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('Username') ?></th>
                    <th><?= $this->Paginator->sort('Name') ?></th>
                    <th><?= $this->Paginator->sort('Enabled') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orcidBatchCreators as $orcidBatchCreator): ?>
                <tr>
                    <?php $ldapResult = $ldapHandler->find('search', [
                        'baseDn' => 'ou=Accounts,dc=univ,dc=pitt,dc=edu',
                        'filter' => 'cn='.$orcidBatchCreator->name,
                        'attributes' => [
                            'displayName',
                        ],
                    ]);
                    if($ldapResult['count'] > 0) {
                        $result = $ldapResult[0];
                        $displayname = $result['displayname'][0];
                    } else {
                        $displayname = '';
                    } ?>
                    <td><?= h($orcidBatchCreator->name) ?></td>
                    <td><?= h($displayname) ?></td>
                    <td><?= $orcidBatchCreator->flags & $orcidBatchCreator::FLAG_DISABLED ? __("No") : __("Yes") ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $orcidBatchCreator->id]) ?>
                        <?php if ($orcidBatchCreator->flags & $orcidBatchCreator::FLAG_DISABLED): ?>
                            <?= $this->Form->postLink(__('Enable'), ['action' => 'enable', $orcidBatchCreator->id]) ?>
                        <?php else: ?>
                            <?= $this->Form->postLink(__('Disable'), ['action' => 'disable', $orcidBatchCreator->id]) ?>
                        <?php endif; ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $orcidBatchCreator->id], ['confirm' => __('Are you sure you want to delete # {0}?', $orcidBatchCreator->id)]) ?>
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
