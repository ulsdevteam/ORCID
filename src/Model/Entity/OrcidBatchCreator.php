<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Core\Configure;
use Exception;

/**
 * OrcidBatchCreator Entity
 *
 * @property int $ID
 * @property string $NAME
 * @property int $FLAGS
 *
 * @property \App\Model\Entity\OrcidBatch[] $orcid_batches
 */
class OrcidBatchCreator extends Entity
{

    const FLAG_DISABLED = 1;

    private $ldapResult;

    private $ldapHandler;

    public function  &__get(string $field)
    {
        if ($this->has($field)) {
            return parent::__get($field);
        } elseif (!(isset($this->ldapResult))) {
            $this->ldapHandler = new \LdapUtility\Ldap(Configure::read('ldapUtility.ldap'));
            $this->ldapHandler->bindUsingCommonCredentials();
            $ldapResult = $this->ldapHandler->find('search', [
                'baseDn' => 'ou=Accounts,dc=univ,dc=pitt,dc=edu',
                'filter' => 'cn=' . $this->NAME,
                'attributes' => [
                    'displayName',
                ],
            ]);
            if ($ldapResult['count'] > 0) {
                $result = $ldapResult[0];
                $this->set('DISPLAYNAME', $result['displayname'][0]);
            } else {
                $this->set('DISPLAYNAME', '');
            }
        }
        return parent::__get($field);
    }

    /**
     * This returns the current status of the flag. This way the function can be expanded
     * if the flag ever becomes something that means more than if it is enabled or disabled.
     * 
     * Note, the return status of this function is true when the flag is disabled currently.
     * False if the flag is disabled.
     * 
     * @param string|null $flag The flag to get the status of.
     * @return integer 1 when disabled, 0 when enabled.
     */
    public function flagStatus($flagName = 'FLAGS') {
        if ($this->has($flagName)) {
            $status = ($this->get($flagName) & $this::FLAG_DISABLED);
            return $status;
        } else {
            throw new Exception("Invalid flagName");
        }
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
        'NAME' => true,
        'FLAGS' => true,
        'orcid_batches' => true,
    ];
}
