<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         1.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Identifier;

use Authentication\Identifier\Resolver\ResolverAwareTrait;
use Authentication\Identifier\Resolver\ResolverInterface;
use Authentication\Identifier\AbstractIdentifier;

/**
 * Creator Identifier
 *
 * Identifies authentication credentials against database containing authenticated users
 *
 * ```
 *  new CreatorIdentifier([
 *      'fields' => [
 *          'username' => ['username'],
 *          'flags' => ['flagName' => ['flagFunctionName' => expectedResult]]]
 *      ]
 *  ]);
 * ```
 */
class CreatorIdentifier extends AbstractIdentifier
{
    use ResolverAwareTrait;
	public const CREDENTIAL_FLAGS = 'flags';

    /**
     * Default configuration.
     * - `fields` The fields to use to identify a user by:
     *   - `username`: one or many username fields.
     *   - `flags`: one or more flags to care about.
     * - `resolver` The resolver implementation to use.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'fields' => [
            self::CREDENTIAL_USERNAME => 'username',
            self::CREDENTIAL_PASSWORD => null,
			self::CREDENTIAL_FLAGS => 'flags'
        ],
        'resolver' => 'Authentication.Orm',
    ];

    /**
     * @inheritDoc
     */
    public function identify(array $data)
    {
        if (!isset($data[self::CREDENTIAL_USERNAME])) {
            return null;
        }

        $identity = $this->_findIdentity($data[self::CREDENTIAL_USERNAME]);
        if (array_key_exists(self::CREDENTIAL_FLAGS, $data)) {
			$flags = $data[self::CREDENTIAL_FLAGS];
            if (!$this->_checkFlags($identity, $flags)) {
                return null;
            }
        }

        return $identity;
    }

    /**
     * Find a user record using the username and flags provided.
     *
     * @param array|\ArrayAccess|null $identity The identity or null.
     * @param array|null $flags The flags to check, they contain the function and the expected result.
     * @return bool
     */
    protected function _checkFlags($identity, $flags): bool
    {
        if ($identity === null) {
            return false;
		}
		
		foreach((array)$flags as $flagName => $flag) {
			$flagFunctionName = key($flag);
			$expectedResult = $flag[$flagFunctionName];
			if (call_user_func([$identity, $flagFunctionName], $flagName) != $expectedResult) {
				return false;
			}
		}

        return true;
    }

    /**
     * Find a user record using the username/identifier provided.
     *
     * @param string $identifier The username/identifier.
     * @return \ArrayAccess|array|null
     */
    protected function _findIdentity(string $identifier)
    {
        $fields = $this->getConfig('fields.' . self::CREDENTIAL_USERNAME);
        $conditions = [];
        foreach ((array)$fields as $field) {
            $conditions[$field] = $identifier;
        }

        return $this->getResolver()->find($conditions, ResolverInterface::TYPE_OR);
    }
}
