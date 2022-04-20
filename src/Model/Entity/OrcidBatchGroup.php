<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * OrcidBatchGroup Entity
 *
 * @property int $id
 * @property string $name
 * @property string|null $group_definition
 * @property string|null $employee_definition
 * @property string|null $student_definition
 * @property \Cake\I18n\FrozenDate|null $cache_creation_date
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
        'name' => true,
        'group_definition' => true,
        'employee_definition' => true,
        'student_definition' => true,
        'cache_creation_date' => true,
        'orcid_batch_group_caches' => true,
        'orcid_batch_triggers' => true,
    ];
}
