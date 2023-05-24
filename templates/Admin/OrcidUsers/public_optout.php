<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrcidUser $orcidUser
 */

use Cake\Collection\Collection;
use Cake\I18n\FrozenTime;

FrozenTime::setToStringFormat("M/dd/YYYY h:mm:ss a");
$this->assign('title', h($user).' Email OptOut');
?>
<aside class="column">
    <nav>
        <div class="navigation actions">
            <h3 class="heading"><?= __('Navigation') ?></h3>
            <?= $this->Html->link(__('Home'), ['controller' => '', 'action' => 'admin', 'prefix' => false]) ?>
        </div>
    </nav>
</aside>
<div class="row">
    <div class="column-responsive column-80">
        <div class="orcidUsers view content">
            <h2><?= h($user).' Email OptOut' ?></h2>
			<table>
                <tr>
                    <th><?= __('Username') ?></th>
                    <td><?= h($user) ?></td>
                </tr>
				<tr>
					<th><?= __('Status') ?></th>
					<td><?= h($status) ?></td>
				</tr>
			</table>
		</div>
	</div>
</div>