<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * OrcidBatchGroup Entity
 *
 * @property int $ID
 * @property string $NAME
 * @property string|null $GROUP_DEFINITION
 * @property string|null $EMPLOYEE_DEFINITION
 * @property string|null $STUDENT_DEFINITION
 * @property \Cake\I18n\FrozenTime|null $CACHE_CREATION_DATE
 *
 * @property \App\Model\Entity\OrcidBatchGroupCache[] $orcid_batch_group_caches
 * @property \App\Model\Entity\OrcidBatchTrigger[] $orcid_batch_triggers
 */
class OrcidBatchGroup extends Entity
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
        'NAME' => true,
        'GROUP_DEFINITION' => true,
        'EMPLOYEE_DEFINITION' => true,
        'STUDENT_DEFINITION' => true,
        'CACHE_CREATION_DATE' => true,
        'orcid_batch_group_caches' => true,
        'orcid_batch_triggers' => true,
    ];
}
