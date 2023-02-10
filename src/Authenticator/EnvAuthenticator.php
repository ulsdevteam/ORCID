<?php

declare(strict_types=1);

namespace App\Authenticator;

use App\Identifier\CreatorIdentifier;
use Authentication\Identifier\IdentifierInterface;
use Psr\Http\Message\ServerRequestInterface;
use Authentication\Authenticator\AbstractAuthenticator;
use Authentication\Authenticator\Result;
use Authentication\Authenticator\ResultInterface;
use Cake\Core\Configure;

class EnvAuthenticator extends AbstractAuthenticator
{
    /**
     * Default config for this object.
     * - `fields` The fields to use to identify a user by.
     * - `skipChallenge` If set to `true` then challenge exception will not be
     *   generated in case of authentication failure. Defaults to `false`.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'fields' => [
            IdentifierInterface::CREDENTIAL_USERNAME => 'NAME',
            CreatorIdentifier::CREDENTIAL_FLAGS => 'FLAGS'
        ],
        'skipChallenge' => true,
        'FORCE_LOWERCASE' => true,
        'DROP_SCOPE' => true,
        'VARIABLE_NAME' => 'REDIRECT_REMOTE_USER',
    ];

    /**
     * Constructor
     *
     * @param \Authentication\Identifier\IdentifierInterface $identifier Identifier or identifiers collection.
     * @param array $config Configuration settings.
     */
    public function __construct(IdentifierInterface $identifier, array $config = [])
    {
        if ((!(array_key_exists('FORCE_LOWERCASE', $config))) && Configure::check('FORCE_LOWERCASE')) {
            $this->setConfig('FORCE_LOWERCASE', Configure::read('FORCE_LOWERCASE'));
        }
        if ((!(array_key_exists('DROP_SCOPE', $config))) && Configure::check('DROP_SCOPE')) {
            $this->setConfig('DROP_SCOPE', Configure::read('DROP_SCOPE'));
        }
        if ((!(array_key_exists('VARIABLE_NAME', $config))) && Configure::check('VARIABLE_NAME')) {
            $this->setConfig('VARIABLE_NAME', Configure::read('VARIABLE_NAME'));
        }
        parent::__construct($identifier, $config);
    }

    /**
     * Authenticate a user using HTTP auth. Will use the configured User model and attempt a
     * login using HTTP auth.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request to authenticate with.
     * @return \Authentication\Authenticator\ResultInterface
     */
    public function authenticate(ServerRequestInterface $request): ResultInterface
    {
        return $this->getUser($request);
    }

    /**
     * Get a user based on information in the request. Used by cookie-less auth for stateless clients.
     *
     * @param ServerRequestInterface $request Request object.
     * @return \Authentication\Authenticator\ResultInterface
     */
    public function getUser(ServerRequestInterface $request)
    {
        //return new Result(null, Result::FAILURE_IDENTITY_NOT_FOUND);
        $server = $request->getServerParams();
        $username = $server[$this->_config['VARIABLE_NAME']] ?? '';
        if ($this->_config['FORCE_LOWERCASE']) {
            $username = strtolower($username);
        }
        if ($this->_config['DROP_SCOPE']) {
            if ($this->_config['DROP_SCOPE'] === true) {
                // Drop any scope/
                $username = preg_replace('/@.*$/', '', $username);
            }
            $scopes = [];
            if (is_string($this->_config['DROP_SCOPE'])) {
                $scopes[] = $this->_config['DROP_SCOPE'];
            } elseif (is_array($this->_config['DROP_SCOPE'])) {
                $scopes = $this->_config['DROP_SCOPE'];
            }
            // Drop a specific scope
            foreach ($scopes as $scope) {
                if (strlen($username) >= strlen($scope) + 1 && strripos($username, '@' . $scope) === strlen($username) - strlen($scope) - 1) {
                    $username = str_ireplace('@' . $scope, '', $username);
                    break;
                }
            }
        }

        if (!is_string($username) || $username === '') {
            return new Result(null, Result::FAILURE_IDENTITY_NOT_FOUND);
        }
        $user = $this->_identifier->identify([IdentifierInterface::CREDENTIAL_USERNAME => $username, CreatorIdentifier::CREDENTIAL_FLAGS => ['FLAGS' => ['flagStatus' => 0]]]);
        if ($user === null) {
            return new Result(null, Result::FAILURE_IDENTITY_NOT_FOUND);
        }

        return new Result($user, Result::SUCCESS);
    }

    /**
     * Generate the login headers
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request Request object.
     * @return array Headers for logging in.
     */
    protected function loginHeaders(ServerRequestInterface $request): array
    {
        return [];
    }
}
