<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\I18n\FrozenTime;
use ArrayObject;

/**
 * OrcidUsers Model
 *
 * @property \App\Model\Table\OrcidBatchGroupCachesTable&\Cake\ORM\Association\HasMany $OrcidBatchGroupCaches
 * @property \App\Model\Table\OrcidEmailsTable&\Cake\ORM\Association\HasMany $OrcidEmails
 * @property \App\Model\Table\OrcidStatusesTable&\Cake\ORM\Association\HasMany $OrcidStatuses
 *
 * @method \App\Model\Entity\OrcidUser newEmptyEntity()
 * @method \App\Model\Entity\OrcidUser newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\OrcidUser[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\OrcidUser get($primaryKey, $options = [])
 * @method \App\Model\Entity\OrcidUser findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\OrcidUser patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\OrcidUser[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\OrcidUser|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OrcidUser saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OrcidUser[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\OrcidUser[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\OrcidUser[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\OrcidUser[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 * 
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class OrcidUsersTable extends Table
{


    public $ldapHandler;
    private $ldapResult;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('ULS.ORCID_USERS');
        $this->setDisplayField('USERNAME');
        $this->setPrimaryKey('ID');

        $this->addBehavior('Timestamp');

        $this->hasMany('AllOrcidStatuses', [
            'foreignKey' => 'ORCID_USER_ID',
        ]);
        $this->hasMany('CurrentOrcidStatuses', [
            'foreignKey' => 'ORCID_USER_ID',
        ])->setStrategy('subquery');
        
        $this->hasMany('OrcidBatchGroupCaches', [
            'foreignKey' => 'ORCID_USER_ID',
        ]);
        $this->hasMany('OrcidEmails', [
            'foreignKey' => 'ORCID_USER_ID',
        ]);
        $this->hasMany('OrcidStatuses', [
            'foreignKey' => 'ORCID_USER_ID',
        ]);

        $this->ldapHandler = new \LdapUtility\Ldap(Configure::read('ldapUtility.ldap'));
        $this->ldapHandler->bindUsingCommonCredentials();
    }

    public function definitionSearch($cn, $attributes = null) {
        $ldapAttributes = ['cn'];
        if (isset($attributes)) {
            foreach ($attributes as $attribute) {
                $ldapAttributes[] = $attribute;
            }
        }
        $this->ldapResult = $this->ldapHandler->find('search',  [
            'baseDn' => 'ou=Accounts,dc=univ,dc=pitt,dc=edu',
            'filter' => $cn,
            'attributes' => $ldapAttributes
        ]);
        $people = [];
        foreach ($this->ldapResult as $person) {
            if (!is_array($person)){
                continue;
            }
            if (isset($attributes)) {
                foreach ($ldapAttributes as $attribute) {
                    $people[$attribute] = $person[$attribute][0];
                }
            } else {
                $people[] = $person['cn'][0];
            }
        }
        return $people;
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('ID')
            ->notEmptyString('ID')
            ->add('ID', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('USERNAME')
            ->maxLength('USERNAME', 8, 'Username must be 8 characters or less.')
            ->requirePresence('USERNAME', 'create', 'Username is required.')
            ->notEmptyString('USERNAME', 'Username is required.')
            ->alphaNumeric('USERNAME', 'Username must be alphanumeric.')
            ->add('USERNAME', 'unique', ['rule' => 'validateUnique', 'provider' => 'table', 'message' => 'Username is already in use.']);

        $validator
            ->scalar('ORCID')
            ->maxLength('ORCID', 19)
            ->allowEmptyString('ORCID')
            ->add('ORCID', 'unique', ['rule' => 'validateUnique', 'provider' => 'table', 'message' => 'ORCID is already in use.'])
            ->add('ORCID', 'formatted', ['rule' => ['custom', '/^(\d{4}-\d{4}-\d{4}-\d{3}[0-9X])$/'], 'message' => 'ORCID must be a valid ORCID format.',])
            ->add('ORCID', 'checksum', ['rule' => [$this, 'orcid_checksum'], 'message' => 'ORCID must yield a valid ORCID checksum.']);

        $validator
            ->scalar('TOKEN')
            ->maxLength('TOKEN', 255)
            ->allowEmptyString('TOKEN')
            ->add('TOKEN', 'UUID', ['rule' => 'uuid', 'message' => 'Scope Token must be a valid UUID.']);

        $validator
            ->dateTime('CREATED')
            ->allowEmptyDateTime('CREATED');

        $validator
            ->dateTime('MODIFIED')
            ->allowEmptyDateTime('MODIFIED');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['ID']), ['errorField' => 'ID']);
        $rules->add($rules->isUnique(['USERNAME']), ['errorField' => 'USERNAME', 'message' => 'Username is already in use.']);
        $rules->add($rules->isUnique(['ORCID'], ['allowMultipleNulls' => true]), ['errorField' => 'ORCID', 'message' => 'ORCID is already in use.']);

        return $rules;
    }

    /**
     * Returns the database connection name to use by default.
     *
     * @return string
     */
    public static function defaultConnectionName(): string
    {
        return (Configure::read('debug')) ? 'default' : 'production-default';
    }

    public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options) {
        if (!$entity->has('CREATED')) {
            $entity->set('CREATED', FrozenTime::now());
        }

        $entity->set('MODIFIED', FrozenTime::now());
        $entity->set('USERNAME', strtoupper($entity->USERNAME));
        return $entity;
    }


    /**
     * Custom validation: ORCID checksum
     * @param string $check
     * @return bool
     */
    public static function orcid_checksum($check) {
		$orcid= str_replace('-', '', $check);
		if (strlen($orcid) != 16) {
			return false;
		}
		$total = 0;
		for ($i=0; $i<15; $i++) {
			$total = ($total + $orcid[$i]) *2;
		}
		
		$remainder = $total % 11;
		$result = (12 - $remainder) % 11;

		return ($orcid[15] == ($result==10 ? 'X' : $result));
    }

}
