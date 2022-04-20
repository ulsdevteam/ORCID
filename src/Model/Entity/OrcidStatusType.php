<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * OrcidStatusType Entity
 *
 * @property int $id
 * @property string $name
 * @property string|null $seq
 *
 * @property \App\Model\Entity\OrcidBatchTrigger[] $orcid_batch_triggers
 * @property \App\Model\Entity\OrcidStatus[] $orcid_statuses
 */
class OrcidStatusType extends Entity
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
        'seq' => true,
        'orcid_batch_triggers' => true,
        'orcid_statuses' => true,
    ];
}
