<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * OrcidBatch Entity
 *
 * @property int $id
 * @property string $name
 * @property string $subject
 * @property string $body
 * @property string $from_name
 * @property string $from_addr
 * @property string|null $reply_to
 * @property int $orcid_batch_creator_id
 *
 * @property \App\Model\Entity\OrcidBatchCreator $orcid_batch_creator
 * @property \App\Model\Entity\OrcidBatchTrigger[] $orcid_batch_triggers
 * @property \App\Model\Entity\OrcidEmail[] $orcid_emails
 */
class OrcidBatch extends Entity
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
        'subject' => true,
        'body' => true,
        'from_name' => true,
        'from_addr' => true,
        'reply_to' => true,
        'orcid_batch_creator_id' => true,
        'orcid_batch_creator' => true,
        'orcid_batch_triggers' => true,
        'orcid_emails' => true,
    ];
}
