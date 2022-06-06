<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Core\Configure;

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



    
    private $ldapHandler;
    private $ldapResult;
    
    public function  &__get(string $field) {
        if ($this->has($field)) {
            return parent::__get($field);
        } else if (!(isset($this->ldapResult))) {
            $this->ldapHandler = new \LdapUtility\Ldap(Configure::read('ldapUtility.ldap'));
            $this->ldapHandler->bindUsingCommonCredentials();

            $this->ldapResult = $this->ldapHandler->find('search', [
                'baseDn' => 'ou=Accounts,dc=univ,dc=pitt,dc=edu',
                'filter' => 'cn='.$this->username,
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
                $this->set("department", $result['department'][0]);

                if(($result['pittemployeerc']['count'] > 0)){
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
