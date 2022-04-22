<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * OrcidBatchTrigger Entity
 *
 * @property int $id
 * @property string $name
 * @property int $orcid_status_type_id
 * @property int $orcid_batch_id
 * @property string $trigger_delay
 * @property int|null $orcid_batch_group_id
 * @property \Cake\I18n\FrozenDate|null $begin_date
 * @property string $repeat_value
 * @property string $maximum_repeat
 *
 * @property \App\Model\Entity\OrcidStatusType $orcid_status_type
 * @property \App\Model\Entity\OrcidBatch $orcid_batch
 * @property \App\Model\Entity\OrcidBatchGroup $orcid_batch_group
 */
class OrcidBatchTrigger extends Entity
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
        'orcid_status_type_id' => true,
        'orcid_batch_id' => true,
        'trigger_delay' => true,
        'orcid_batch_group_id' => true,
        'begin_date' => true,
        'repeat_value' => true,
        'maximum_repeat' => true,
        'orcid_status_type' => true,
        'orcid_batch' => true,
        'orcid_batch_group' => true,
    ];
}
