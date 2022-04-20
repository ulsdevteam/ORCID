<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * OrcidStatus Entity
 *
 * @property int $id
 * @property int $orcid_user_id
 * @property int $orcid_status_type_id
 * @property \Cake\I18n\FrozenDate $status_timestamp
 *
 * @property \App\Model\Entity\OrcidUser $orcid_user
 * @property \App\Model\Entity\OrcidStatusType $orcid_status_type
 */
class OrcidStatus extends Entity
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
        'orcid_user_id' => true,
        'orcid_status_type_id' => true,
        'status_timestamp' => true,
        'orcid_user' => true,
        'orcid_status_type' => true,
    ];
}
