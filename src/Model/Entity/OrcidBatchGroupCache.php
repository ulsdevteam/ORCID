<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * OrcidBatchGroupCache Entity
 *
 * @property int $ID
 * @property int $ORCID_BATCH_GROUP_ID
 * @property int $ORCID_USER_ID
 * @property \Cake\I18n\FrozenTime|null $DEPRECATED
 *
 * @property \App\Model\Entity\OrcidBatchGroup $orcid_batch_group
 * @property \App\Model\Entity\OrcidUser $orcid_user
 */
class OrcidBatchGroupCache extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'ID' => true,
        'ORCID_BATCH_GROUP_ID' => true,
        'ORCID_USER_ID' => true,
        'DEPRECATED' => true,
        'orcid_batch_group' => true,
        'orcid_user' => true,
    ];
}
