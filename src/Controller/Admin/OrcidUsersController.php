<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use App\Model\Entity\OrcidStatusType;
use Cake\Http\Exception\NotFoundException;
use Cake\I18n\FrozenTime;
use Cake\Collection\Collection;
use Cake\Core\Configure;
use Cake\Utility\Xml;
use Cake\Http\Client;
use App\Exception\UnrecognizedOrcidException;
use Cake\Database\Expression\QueryExpression;
use Cake\Database\Query;
use Cake\Http\Cookie\Cookie;
use Cake\Log\Log;
use Exception;

use function PHPUnit\Framework\equalTo;

/**
 * OrcidUsers Controller
 *
 * @property \App\Model\Table\OrcidUsersTable $OrcidUsers
 * @method \App\Model\Entity\OrcidUser[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class OrcidUsersController extends AppController
{

    private const CONTAINS = '0';
    private const STARTS_WITH = '1';
    private const ENDS_WITH = '2';
    private const EXACTLY = '3';
    private const NULL_STRING_ID = '-1';
    private const NULL_ID = -1;
    private $OAUTH_PATH = '';

    /**
     * Initialize Method
     * 
     * @return void Sets OAUTH_PATH at beginning of creation
     */

    public function initialize(): void
    {
        $this->OAUTH_PATH = "Resources.".(Configure::read('debug') ? 'default-orcid' : 'production-orcid').'.';
        parent::initialize();
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $orcidUsers = $this->paginate($this->OrcidUsers);

        $this->set(compact('orcidUsers'));
    }

    /**
     * View method
     *
     * @param string|null $id Orcid User id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $orcidUser = $this->OrcidUsers->get($id, [
            'contain' => ['OrcidEmails', 'CurrentOrcidStatuses', 'CurrentOrcidStatuses.OrcidStatusTypes', 'AllOrcidStatuses.OrcidStatusTypes'],
        ]);
        $this->set(compact('orcidUser'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $orcidUser = $this->OrcidUsers->newEmptyEntity();
        if ($this->request->is('post')) {
            $orcidUser = $this->OrcidUsers->patchEntity($orcidUser, $this->request->getData());
            if ($this->OrcidUsers->save($orcidUser)) {
                $this->Flash->success(__('The orcid user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The orcid user could not be saved. Please, try again.'));
        }
        $this->set(compact('orcidUser'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Orcid User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $orcidUser = $this->OrcidUsers->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $orcidUser = $this->OrcidUsers->patchEntity($orcidUser, $this->request->getData());
            if ($this->OrcidUsers->save($orcidUser)) {
                $this->Flash->success(__('The orcid user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The orcid user could not be saved. Please, try again.'));
        }
        $this->set(compact('orcidUser'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Orcid User id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $orcidUser = $this->OrcidUsers->get($id);
        if ($this->OrcidUsers->delete($orcidUser)) {
            $this->Flash->success(__('The orcid user has been deleted.'));
        } else {
            $this->Flash->error(__('The orcid user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * optout method
     *
     * @param string|null $id Orcid User id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Http\Exception\NotFoundException
     */
    public function optout($id = null)
    {
        if (!$this->OrcidUsers->exists($id)) {
            throw new NotFoundException(__('Invalid ORCID User'));
        }
        if ($this->request->is(['post', 'put'])) {
            $OrcidStatusTable = $this->fetchTable('OrcidStatuses');
            $OrcidStatusTypesTable = $this->fetchTable('OrcidStatusTypes');
            $orcidStatusTypeID = $OrcidStatusTypesTable->find()->where(['SEQ' => $OrcidStatusTypesTable::OPTOUT_SEQUENCE])->first()->id;
            $orcidStatuses = $OrcidStatusTable->find()->where(['ORCID_USER_ID' => $id, 'ORCID_STATUS_TYPE_ID' =>  $orcidStatusTypeID])->first();

            if (isset($orcidStatuses)) {
                var_dump("Opted out already");
                $this->Flash->error(__('The ORCID User has already opted out.'));
                return $this->redirect(['action' => 'index']);
            }
            $time = FrozenTime::now();
            $data = [
                'ORCID_USER_ID' => $id,
                'ORCID_STATUS_TYPE_ID' => $orcidStatusTypeID,
                'STATUS_TIMESTAMP' => $time
            ];
            $OptOutStatus = $OrcidStatusTable->newEntity($data);
            if ($OrcidStatusTable->save($OptOutStatus)) {
                $this->Flash->success(__('The ORCID Opt-out has been saved.'));
            } else {
                $this->Flash->error(__('The ORCID Opt-out could not be saved. Please, try again.'));
            }
            return $this->redirect(['action' => 'index']);
        }
    }

    /**
     * find method
     *
     * @return \Cake\Http\Response|null|void Redirects to index.
     */
    public function find()
    {
        $options = $this->request->getQueryParams();

        if ($options) {
            $userQuery = $options['q'] ?? '';
            $findType = $options['s'] ?? '';
            $groupQuery = $options['g'] ?? '';
        } else {
            $userQuery = '';
            $findType = '';
            $groupQuery = '';
        }

        $BatchGroups = $this->fetchTable('OrcidBatchGroups');
        $batchGroups = $BatchGroups->find('all')->all();
        $orcidUsers = $this->paginate($this->_parameterize($userQuery, $findType, $groupQuery));

        $findTypes = ['Containing', 'Starting With', 'Ending With', 'Matching Exactly'];
        $groups = [0 => ''];

        foreach ($batchGroups as $group) {
            $groups[$group->ID] = $group->NAME;
        }

        $groups[$this::NULL_ID] = 'No Matching Group';

        $this->set('findTypes', $findTypes);
        $this->set('userQuery', $userQuery);
        $this->set('selectedType', $findType);
        $this->set('selectedGroup', $groupQuery);
        $this->set('batchGroups', $groups);
        $this->set(compact('orcidUsers'));
    }

    private function _parameterize($userQuery, $findType, $groupQuery)
    {

        // container to hold conditions
        $conditions = [];

        // Starting point for query
        $orcidUsersTable = $this->OrcidUsers->find('all')->contain(['CurrentOrcidStatuses', 'CurrentOrcidStatuses.OrcidStatusTypes']);

        // query by string matching
        if (!empty($userQuery)) {
            if ($findType === $this::EXACTLY) {
                $conditions = ['OR' => [['USERNAME' => strtoupper($userQuery)], ['ORCID' => $userQuery]]];
            } else if ($findType === $this::ENDS_WITH) {
                $conditions = ['OR' => [['USERNAME LIKE' => '%' . strtoupper($userQuery)], ['ORCID LIKE' => '%' . $userQuery]]];
            } else if ($findType === $this::STARTS_WITH) {
                $conditions = ['OR' => [['USERNAME LIKE' => strtoupper($userQuery) . '%'], ['ORCID LIKE' => $userQuery . '%']]];
            } else if ($findType === $this::CONTAINS) {
                $conditions = ['OR' => [['USERNAME LIKE' => '%' . strtoupper($userQuery) . '%'], ['ORCID LIKE' => '%' . $userQuery . '%']]];
            }
        }

        // query by group
        if (!empty($groupQuery)) {
            if ($groupQuery === $this::NULL_STRING_ID) {
                // notMatching creates a left join
                $orcidUsersTable = $orcidUsersTable->notMatching('OrcidBatchGroupCaches', function ($q) use ($groupQuery) {
                    return $q->where(['OrcidBatchGroupCaches.ORCID_BATCH_GROUP_ID IS NOT' => null]);
                });
            } else {
                // matching creates an inner join
                $orcidUsersTable = $orcidUsersTable->matching('OrcidBatchGroupCaches', function ($q) use ($groupQuery) {
                    return $q->where(['OrcidBatchGroupCaches.ORCID_BATCH_GROUP_ID' => $groupQuery]);
                });
            }
        }

        // if no query specified, return nothing
        if (empty($userQuery) && empty($groupQuery)) {
            // no ORCID id should be -1
            $conditions = ['ORCID' => $this::NULL_ID];
        }

        // this is the final query after all conditions
        $orcidUsersQuery = $orcidUsersTable->where($conditions);

        return $orcidUsersQuery;
    }

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        $this->Authentication->allowUnauthenticated(['connect']);
        parent::beforeFilter($event);
    }
    
    // The adding of a new ORCID user shouldn't be too bad with it being inside the cakephp
    // application The main issue is understanding and rewriting the actual connection.
    public function connect() {
        // Grab the remote user from Shibboleth
        $remote_user = filter_var($_SERVER['REDIRECT_REDIRECT_eppn'], FILTER_SANITIZE_STRING);
        $remote_user = preg_replace('/@pitt.edu$/i', '', $remote_user);
        // Grab variables from Shibboleth
        $shib_gn = filter_var($_SERVER['REDIRECT_REDIRECT_givenName'], FILTER_SANITIZE_STRING);
        $shib_ln = filter_var($_SERVER['REDIRECT_REDIRECT_sn'], FILTER_SANITIZE_STRING);
        $shib_mail = filter_var($_SERVER['REDIRECT_REDIRECT_mail'], FILTER_SANITIZE_EMAIL); 
        $shib_affiliations = explode(';', filter_var($_SERVER['REDIRECT_REDIRECT_PittAffiliate'], FILTER_SANITIZE_STRING)); 
        $shib_groups = explode(';', filter_var($_SERVER['REDIRECT_REDIRECT_PittCustomGroupMembership'], FILTER_SANITIZE_STRING)); 

        if (in_array('employee', $shib_affiliations, TRUE) || in_array('faculty', $shib_affiliations, TRUE) || in_array('staff', $shib_affiliations, TRUE)) {
            $orcid_affiliations = array('employment');
        } else {
            $orcid_affiliations = array();
        }
        if (in_array('FERPA', $shib_groups, TRUE)) {
            $orcid_affiliations[] = 'FERPA';
        }
    
        $ORCID_LOGIN = Configure::read($this->OAUTH_PATH."ORCID_LOGIN");

        // This default success message will be used multiple places
        // Check for ORCID sending us an error message
        if (isset($_GET['error'])) {
            switch ($_GET['error']) {
                case 'access_denied':
                    // user explicitly denied us access (maybe)
                    // ORCID's workflow is a little off - a user can click deny without actually logging in
                    // Clear the existing token if we've lost permission
                    $user = $this->OrcidUsers->find("all")->where(["USERNAME" => strtoupper($remote_user)])->first();
                    //$row = $this->execute_query_or_die($conn, 'SELECT ORCID, TOKEN FROM ULS.ORCID_USERS WHERE USERNAME = :shibUser', array('shibUser' => strtoupper($remote_user)));
                    if (isset($user)) {
                        // Yes, the user exists.  Do we already have a valid ORCID and token?
                        if (isset($user->ORCID) && isset($user->TOKEN)) {
                            if (!$this->validate_record($user->ORCID, $user->TOKEN, $remote_user, $orcid_affiliations)) {
                                //$this->execute_query_or_die($conn, 'UPDATE ULS.ORCID_USERS SET MODIFIED = SYSDATE, TOKEN = :token WHERE USERNAME = :shibUser', array('shibUser' => strtoupper($remote_user), 'token' => ''));
                                $user->set("TOKEN", "");
                                $user->set("MODIFIED", FrozenTime::now());
                            }
                        }
                    }
                    // Ask if the user meant to do that
                    $this->viewBuilder()->setTemplatePath("connect")->setTemplate('untrusted')->setLayout('public');
                    return;
                default:
                    // ORCID could send a different error message, but that isn't handled (yet)
                    $this->die_with_error_page('500 Unrecognized ORCID error');
                    return;
                    /* error_log(var_export($_GET, true));
                    throw new UnrecognizedOrcidException($_GET['error']); */
            }
            // The switch should have exit()'d for us
        } else if (!isset($_GET['code'])) {
            // If we don't have a CODE from ORCID,
            // We are in the workflow before the redirect to ORCID
            // Check the status of the current user
            // Possible outcomes of this conditional are:
            //   An HTTP error message
            //   A redirect to the success message
            //   A pass through to the sendoff to ORCID
            // Does this user exist?
            // $row = $this->execute_query_or_die($conn, 'SELECT ORCID, TOKEN, USERNAME FROM ULS.ORCID_USERS WHERE USERNAME = :shibUser', array('shibUser' => strtoupper($remote_user)));
            $user = $this->OrcidUsers->find("all")
            ->where(["USERNAME" => strtoupper($remote_user)])
            ->first();
            if (isset($user) && isset($user->USERNAME)) {
                // Yes, the user exists.  Do we already have a valid ORCID and token?
                if (isset($user->ORCID) && isset($user->TOKEN)) {
                    if ($this->validate_record($user->ORCID, $user->TOKEN, $remote_user, $orcid_affiliations)) {
                        // Yes, we already have a valid ORCID and token.  Send a success message and exit
                        $this->viewBuilder()->setTemplatePath("connect")->setTemplate('success')->setLayout('public')->setVar("user", $user)->setVar("ORCID_LOGIN", $ORCID_LOGIN);
                        return;
                    }
                }
            } else {
                // This user doesn't exist yet.  Add them.
                // $this->execute_query_or_die($conn, 'INSERT INTO ULS.ORCID_USERS (USERNAME, CREATED, MODIFIED) VALUES (:shibUser, SYSDATE, SYSDATE)', array('shibUser' => strtoupper($remote_user)));
                $user = $this->OrcidUsers->newEmptyEntity();
                $user->USERNAME = strtoupper($remote_user);
                $user->CREATED = FrozenTime::now();
                $user->MODIFIED = FrozenTime::now();
                try {
                    $result = $this->OrcidUsers->save($user);
                } catch (Exception $e) {
                    $result = false;
                    Log::write('error', 'ORCID@PITT: ' . $e);
                }
                if ($result === false) {
                    $this->die_with_error_page("500 ORCID@Pitt Database Error");
                    return;
                }
            }

            // If we haven't exited to this point, note that the user has visited and we are going to redirect them to ORCID

            $OrcidStatuses = $this->fetchTable('OrcidStatuses');

            $orcidStatusQuery = $OrcidStatuses->find('all')
            ->select('ID')
            ->where(function (QueryExpression $exp, Query $q) {
                return $exp->equalFields('OrcidStatuses.ORCID_STATUS_TYPE_ID', 'a.ID');
            })->andWhere(function (QueryExpression $exp, Query $q) {
                return $exp->equalFields('OrcidStatuses.ORCID_USER_ID', 'OrcidUsers.ID');
            });

            $user = $this->OrcidUsers->find('all')
            ->select(['ORCID_USER_ID' => 'OrcidUsers.ID', 'ORCID_STATUS_TYPE_ID' => 'a.ID'])
            ->join([
                'a' => [
                    'table' => 'ULS.ORCID_STATUS_TYPES',
                    'conditions' => [
                        'OrcidUsers.USERNAME' => strtoupper($remote_user),
                        'a.SEQ' => 2,
                    ],
                ],
            ])
            ->where(function (QueryExpression $exp, Query $q) use ($orcidStatusQuery) {
                return $exp->notExists($orcidStatusQuery);
            });

            $newUser = $user->first();
            if (isset($newUser)) {
                $newStatus = $OrcidStatuses->newEmptyEntity();
                $newStatus->ORCID_USER_ID = $newUser->ORCID_USER_ID;
                $newStatus->ORCID_STATUS_TYPE_ID = $newUser->ORCID_STATUS_TYPE_ID;
                $newStatus->STATUS_TIMESTAMP = FrozenTime::now();
                try {
                    $result = $OrcidStatuses->save($newStatus);
                } catch (Exception $e) {
                    $result = false;
                    Log::write('error', 'ORCID@PITT: ' . $e);
                }
                if ($result === false) {
                    $this->die_with_error_page("500 ORCID@Pitt Database Error");
                    return;
                }
            }

            // For the ORCID sandbox, use mailinator URLS
            if (Configure::read('debug')) {
                $shib_mail = str_replace('@', '.', $shib_mail).'@mailinator.com';
            }

            // redirect to ORCID
            $state = bin2hex(openssl_random_pseudo_bytes(16));
            setcookie('oauth_state', $state, [
                'expires' => (time() + 3600),
                'httponly' => true
            ]);
            $url = Configure::read($this->OAUTH_PATH."OAUTH_AUTHORIZATION_URL") . '?' . http_build_query(array(
                'response_type' => 'code',
                'client_id' => Configure::read($this->OAUTH_PATH."OAUTH_CLIENT_ID"),
                'redirect_uri' => Configure::read($this->OAUTH_PATH."OAUTH_REDIRECT_URI"),
                'scope' => Configure::read("Resources.OAUTH_SCOPE"),
                'state' => $state,
                'given_names' => $shib_gn,
                'family_names' => $shib_ln,
                'email' => $shib_mail,
                // ORCID bug: https://trello.com/c/Y0dqjqId/362-authorization-code-not-generated-when-signed-in-user-visits-link-with-orcid-parameter
                // ULS ticket: https://ulstracker.atlassian.net/browse/SYSDEV-1615
                'orcid' => isset($row['ORCID']) ? $row['ORCID'] : '',
            ));
            $this->redirect($url);
            return;
        }
        // We handled ORCID errors and initial touches before the ORCID handoff above.
        // Since we are here, this must mean we are returning from ORCID and have a CODE
        // If we are, we expect a matching session state
        if (!isset($_GET['state']) || !isset($_COOKIE['oauth_state']) || $_GET['state'] !== $_COOKIE['oauth_state']) {
            Log::write('critical', 'CRITICAL: GET parameters do not match COOKIE values.');
            Log::write('critical', 'CRITICAL: GET: '.var_export($_GET, true));
            Log::write('critical', 'CRITICAL: COOKIE: '.var_export($_COOKIE, true));
            $this->die_with_error_page('403 Invalid parameters');
            return;
        }

        // 
        // fetch the access token
        $client = new Client();
        $result = $client->post(Configure::read($this->OAUTH_PATH.'OAUTH_TOKEN_URL'), [
            'code' => $_GET['code'],
            'grant_type' => 'authorization_code',
            'client_id' => Configure::read($this->OAUTH_PATH.'OAUTH_CLIENT_ID'),
            'client_secret' => Configure::read($this->OAUTH_PATH.'OAUTH_CLIENT_SECRET'),
            'redirect_uri' => Configure::read($this->OAUTH_PATH.'OAUTH_REDIRECT_URI'),
            'scope' => '',
        ], 
        [
            'headers' =>[
                'Accept' => 'application/json',
            ],
            'curl' => [
                CURLINFO_HEADER_OUT => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS => 5,
            ]
        ]);
        
        $response = $result->getJson();
        if (isset($response['orcid'])) {
            if (!$this->validate_record($response['orcid'], $response['access_token'], $remote_user, $orcid_affiliations)) {
                $this->die_with_error_page('500 ORCID Validation error');
                return;
            }
            // Update ORCID and TOKEN as returned
            $user = $this->OrcidUsers->find("all")
            ->where(["USERNAME" => strtoupper($remote_user)])
            ->first();
            $user->MODIFIED = FrozenTime::now();
            $user->ORCID = $response['orcid'];
            $user->TOKEN = $response['access_token'];
            try {
                $result = $this->OrcidUsers->save($user);
            } catch (Exception $e) {
                $result = false;
                Log::write('error', 'ORCID@PITT: ' . $e);
            }
            if ($result === false) {
                $this->die_with_error_page("500 ORCID@Pitt Database Error");
                return;
            }
            //execute_query_or_die($conn, 'UPDATE ULS.ORCID_USERS SET MODIFIED = SYSDATE, ORCID = :orcid, TOKEN = :token WHERE USERNAME = :shibUser', array('shibUser' => strtoupper($remote_user), 'token' => $response['access_token'], 'orcid' => $response['orcid']));
        } else {
            $this->die_with_error_page('500 ORCID API connection error');
            return;
        }

        $this->viewBuilder()->setTemplatePath("connect")->setTemplate('success')->setLayout('public')->setVar("user", $user)->setVar("ORCID_LOGIN", $ORCID_LOGIN);
    }

    /**
     * Read an ORCID record, returning XML
     * 
     * @param string $orcid ORCID Id
     * @param string $token ORCID access token
     * @return string XML on success
     */
    function read_profile($orcid, $token) {
        $client = new Client();
        $result = $client->get(Configure::read($this->OAUTH_PATH.'OAUTH_API_URL').$orcid.'/record', null, [
            'headers' =>[
                'Content-Type' => 'application/vdn.orcid+xml',
                'Authorization' => 'Bearer ' . $token,
            ],
            'curl' => [
                CURLINFO_HEADER_OUT => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS => 5,
            ]]);
            $result->getXml()->asXML();
        if ($result->getStatusCode() == 200) {
            return $result->getXml();
        } else {
            return false;
        }
    }

    /**
     * Write the External ID to ORCID if external id was not created earlier
     * 
     * @param string $orcid ORCID Id
     * @param string $token ORCID access token
     * @param string $id External ID
     * @return boolean success
     */
    function write_extid($orcid, $token, $id) {
        $payload = Xml::build(Configure::read('App.wwwRoot').'xml/external-identifier.xml', ['readFile' => true]);
        $client = new Client();
        $payloadChildren = $payload->children("common", true);
        $payloadChildren->{"external-id-value"} = $id;
        $payloadChildren->{"external-id-url"} = Configure::read($this->OAUTH_PATH."EXTERNAL_WEBHOOK").'?id='.$id;
        $result = $client->post(Configure::read($this->OAUTH_PATH.'OAUTH_API_URL').$orcid.'/external-identifiers', $payload->asXML(), [
        'type' => 'xml',
        'headers' => [
            'Content-Type' => 'application/vdn.orcid+xml',
            'Authorization' => 'Bearer '.$token,
        ],
        'curl' => [
            CURLINFO_HEADER_OUT => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 5,
        ]]);
        if ( $result->getStatusCode() == 201 || $result->getStatusCode() == 200) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Write the Affiliation to ORCID if affiliations were not created earlier
     * 
     * @param string $orcid ORCID Id
     * @param string $token ORCID access token
     * @param string $type Affiliation type (invalid types will be ignored)
     * @return boolean success
     */
    function write_affiliation($orcid, $token, $type) {
        if ($type !== 'employment' && $type !== 'education') {
            return true;
        }
        if($type == 'employment') {
            $payload = Xml::build(Configure::read('App.wwwRoot').'xml/employment.xml', ['readFile' => true]);
        } else { 
            $payload = Xml::build(Configure::read('App.wwwRoot').'xml/education.xml', ['readFile' => true]);
        }
        $client = new Client();
        $result = $client->post(Configure::read($this->OAUTH_PATH.'OAUTH_API_URL').$orcid.'/'.$type, $payload->asXML(), [
        'type' => 'xml',
        'headers' => [
            'Content-Type' => 'application/vdn.orcid+xml',
            'Authorization' => 'Bearer '.$token,
        ],
        'curl' => [
            CURLINFO_HEADER_OUT => true,
            ]]);
        if ( $result->getStatusCode() == 201 || $result->getStatusCode() == 200) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Check whether the Pitt Affiliation exists in the ORCID profile
     * 
     * @param SimpleXMLElement $xml
     * @return boolean
     */
    function read_affiliation($xml, $type) {
        try {
            $xml->registerXPathNamespace('o', "http://www.orcid.org/ns/activities");
            $xml->registerXPathNamespace('e', "http://www.orcid.org/ns/".$type);
            $xml->registerXPathNamespace('c', "http://www.orcid.org/ns/common");
            // Check if disambiguation source exists for education or employment by matching with our disambiguation-source and disambiguation-organization-identifier
            $elements = $xml->xpath('//e:'.$type.'-summary[e:organization/c:disambiguated-organization[c:disambiguation-source[text()="'.Configure::read("Resources.PITT_AFFILIATION_KEY").'"] and c:disambiguated-organization-identifier[text()="'.Configure::read("Resources.PITT_AFFILIATION_ID").'"]]]'); 
        // NOT SURE HOW THIS WILL WORK, we'll see in live testing.
        } catch (Exception $e) {
            error_log($e);
            return false;
        }
        return !empty($elements);
    }


    /**
     * Check whether the Pitt ID exists in the ORCID profile
     * 
     * @param SimpleXMLElement $xml
     * @return boolean true if it does exists, false if it does not.
     */
    function read_extid($xml) {
        try {
            $xml->registerXPathNamespace('o', "http://www.orcid.org/ns/common");
            // Check on an external ID with our common name
            $elements = $xml->xpath('//o:external-id-type[text()="'.Configure::read("Resources.PITT_EXTID_NAME").'"]');
        } catch (Exception $e) {
            error_log($e);
            return false;
        }
        return !empty($elements);
    }

    /**
     * Verify that an ORCID has our custom fields set.  If unset, set them.
     * 
     * @param string $orcid
     * @param string $token
     * @param array $affiliations
     * @return true if record could be validated; false if any error occurred
     */
    function validate_record($orcid, $token, $user, $affiliations = array()) {
        $profile = $this->read_profile($orcid, $token);
        if ($profile) {
            // The profile should have an External ID unless the the FERPA flag is present on a student-only record
            if ((!in_array('FERPA', $affiliations) || in_array('employment', $affiliations)) && !$this->read_extid($profile)) {
                if (!$this->write_extid($orcid, $token, $user)) {
                    return false;
                }
            }
            // The profile should have each affiliation, unless the FERPA flag blocks a student affiliation
            // It is up to the caller to only pass the desired affiliations (e.g. writing employment but not education)
            // write_affiliation will filter only valid ORCID profile affiliations (e.g. ignoring FERPA as a key)
            foreach ($affiliations as $affiliation) {
                if ($affiliation == 'education' && in_array('FERPA', $affiliations)) {
                    continue;
                }
                if (!$this->read_affiliation($profile, $affiliation)) {
                    if (!$this->write_affiliation($orcid, $token, $affiliation)) {
                        return false;
                    }
                }
            }
            return true;
        }
        return false;
    }
    
    /**
     * Generate an error page based on a HTTP error code and message
     * @param string $error
     */
    function die_with_error_page($error) {
        $this->viewBuilder()->setTemplatePath("Error")->setTemplate('orciderror')->setLayout('public')->setVar('error', $error);
    }

}
