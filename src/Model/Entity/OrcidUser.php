<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Core\Configure;
use Cake\ORM\Locator\LocatorAwareTrait;
/**
 * OrcidUser Entity
 *
 * @property int $ID
 * @property string $USERNAME
 * @property string|null $ORCID
 * @property string|null $TOKEN
 * @property \Cake\I18n\FrozenTime|null $CREATED
 * @property \Cake\I18n\FrozenTime|null $MODIFIED
 *
 * @property \App\Model\Entity\AllOrcidStatus[] $all_orcid_statuses
 * @property \App\Model\Entity\CurrentOrcidStatus[] $current_orcid_status
 * @property \App\Model\Entity\OrcidBatchGroupCache[] $orcid_batch_group_caches
 * @property \App\Model\Entity\OrcidEmail[] $orcid_emails
 * @property \App\Model\Entity\OrcidStatus[] $orcid_statuses
 */
class OrcidUser extends Entity
{


    private $ldapResult;

    private $ldapHandler;

    use LocatorAwareTrait;
    
    public function  &__get(string $field) {
        if ($this->has($field)) {
            return parent::__get($field);
        } else if (!(isset($this->ldapResult))) {
            $OrcidUsersTable = $this->fetchTable('OrcidUsers');

            $this->ldapHandler = $OrcidUsersTable->ldapHandler;

            $this->ldapResult = $this->ldapHandler->find('search', [
                'baseDn' => 'ou=Accounts,dc=univ,dc=pitt,dc=edu',
                'filter' => 'cn='.$this->USERNAME,
                'attributes' => [
                    'mail',
                    'displayName',
                    'department',
                    'PittEmployeeRC',
                ],
            ]);

            if($this->ldapResult['count'] > 0) {

                $result = $this->ldapResult[0];
                $this->set("displayname", $result['displayname'][0]);
                $this->set("email", $result['mail'][0]);
                if (!isset($result['department'])) {
                    $this->set("department", "");
                } else {
                    $this->set("department", $result['department'][0]);
                }

                if (!isset($result['pittemployeerc'])) {
                    $this->set("department", "");
                    $this->set("rc", "");
                } elseif (($result['pittemployeerc']['count'] > 0)) {
                    $this->set("rc", $result['pittemployeerc'][0]);
                    $this->set("rcdepartment", "RC ".$result['pittemployeerc'][0]." / ".$result['department'][0]);
                } else {
                    $this->set("rcdepartment", $result['department'][0]);
                }

            } else {

                $this->set("name", "");
                $this->set("email", "");
                $this->set("department", "");
                $this->set("rc", "");

            }
        }
        return parent::__get($field);
    }
    
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
        'USERNAME' => true,
        'ORCID' => true,
        'TOKEN' => true,
        'CREATED' => true,
        'MODIFIED' => true,
        'all_orcid_statuses' => true,
        'current_orcid_status' => true,
        'orcid_batch_group_caches' => true,
        'orcid_emails' => true,
        'orcid_statuses' => true,
    ];
}
