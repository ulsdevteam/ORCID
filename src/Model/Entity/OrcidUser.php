<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * OrcidUser Entity
 *
 * @property int $id
 * @property string $username
 * @property string|null $orcid
 * @property string|null $token
 * @property \Cake\I18n\FrozenDate|null $created
 * @property \Cake\I18n\FrozenDate|null $modified
 *
 * @property \App\Model\Entity\OrcidBatchGroupCache[] $orcid_batch_group_caches
 * @property \App\Model\Entity\OrcidEmail[] $orcid_emails
 * @property \App\Model\Entity\OrcidStatus[] $orcid_statuses
 */
class OrcidUser extends Entity
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
        'username' => true,
        'orcid' => true,
        'token' => true,
        'created' => true,
        'modified' => true,
        'orcid_batch_group_caches' => true,
        'orcid_emails' => true,
        'orcid_statuses' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array<string>
     */
    protected $_hidden = [
        'token',
    ];
}
