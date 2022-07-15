<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * OrcidBatch Entity
 *
 * @property int $ID
 * @property string $NAME
 * @property string $SUBJECT
 * @property string $BODY
 * @property string $FROM_NAME
 * @property string $FROM_ADDR
 * @property string|null $REPLY_TO
 * @property int $ORCID_BATCH_CREATOR_ID
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
        'ID' => true,
        'NAME' => true,
        'SUBJECT' => true,
        'BODY' => true,
        'FROM_NAME' => true,
        'FROM_ADDR' => true,
        'REPLY_TO' => true,
        'ORCID_BATCH_CREATOR_ID' => true,
        'ORCID_BATCH_CREATOR' => true,
        'ORCID_BATCH_TRIGGERS' => true,
        'ORCID_EMAILS' => true,
    ];
}
