<?php
declare(strict_types=1);

namespace App\Authentication;

use Authentication\Identifier\IdentifierInterface;
use Psr\Http\Message\ServerRequestInterface;
use Authentication\Authenticator\HttpBasicAuthenticator;
use Authentication\Authenticator\Result;
use Authentication\Authenticator\ResultInterface;

class EnvAuth extends HttpBasicAuthenticator {
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
            IdentifierInterface::CREDENTIAL_USERNAME => 'username',
            IdentifierInterface::CREDENTIAL_PASSWORD => 'password',
        ],
    ];

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
public function getUser(ServerRequestInterface $request) {
	$username = env('TEST');
	if (isset($this->settings['FORCE_LOWERCASE']) && $this->settings['FORCE_LOWERCASE']) {
		$username = strtolower($username);
	}
	if (isset($this->settings['DROP_SCOPE'])) {
		if ($this->settings['DROP_SCOPE'] === true) {
			// Drop any scope
			$username = preg_replace('/@.*$/', '', $username);
		}
		$scopes = array();
		if (is_string($this->settings['DROP_SCOPE'])) {
			$scopes[] = $this->settings['DROP_SCOPE'];
		} elseif (is_array($this->settings['DROP_SCOPE'])) {
			$scopes = $this->settings['DROP_SCOPE'];
		}
		// Drop a specific scope
		foreach ($scopes as $scope) {
			if (strlen($username) >= strlen($scope) + 1 && strripos($username, '@'.$scope) === strlen($username) - strlen($scope) - 1) {
				$username = str_ireplace('@'.$scope, '', $username);
				break;
			}
		}
	}

	if (!is_string($username) || $username === '') {
		return false;
	}
	return $

    /**
     * Generate the login headers
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request Request object.
     * @return array Headers for logging in.
     */
    protected function loginHeaders(ServerRequestInterface $request): array
    {
        $server = $request->getServerParams();
        $realm = $this->getConfig('realm') ?: $server['SERVER_NAME'];

        return ['WWW-Authenticate' => sprintf('Basic realm="%s"', $realm)];
    }
}