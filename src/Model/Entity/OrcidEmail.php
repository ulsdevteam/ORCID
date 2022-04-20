<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * OrcidEmail Entity
 *
 * @property int $id
 * @property int $orcid_user_id
 * @property int $orcid_batch_id
 * @property \Cake\I18n\FrozenDate|null $queued
 * @property \Cake\I18n\FrozenDate|null $sent
 * @property \Cake\I18n\FrozenDate|null $cancelled
 *
 * @property \App\Model\Entity\OrcidUser $orcid_user
 * @property \App\Model\Entity\OrcidBatch $orcid_batch
 */
class OrcidEmail extends Entity
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
        'orcid_batch_id' => true,
        'queued' => true,
        'sent' => true,
        'cancelled' => true,
        'orcid_user' => true,
        'orcid_batch' => true,
    ];
}
