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
    /** 
     * FIND & REPORT function constants 
     * The first four are for the selected query type.
    */
    private const CONTAINS = '0';
    private const STARTS_WITH = '1';
    private const ENDS_WITH = '2';
    private const EXACTLY = '3';
    // This one is used mainly for the current status when one isn't selected
    // It is also used when selecting the no matching group for group queries
    private const NULL_STRING_ID = '-1';
    // This is used when not selecting any group. This is different from no matching group.
    private const EMPTY_STRING_ID = 0;
    // This is used for the value/key for the arrays when it comes to the groups and statuses.
    // In the case of groups, it is the integer version of the NULL_STRING_ID and for the statuses
    // it is the case when no status is selected incase a status has an id of 0.
    // It is also used when not finding any users, to force the find to be populated with nothing.
    private const NULL_ID = -1;

    /**
     * CONNECT function constants
     */
    private const SHIB_USERNAME = 'REDIRECT_REDIRECT_eppn';
    private const SHIB_GIVEN_NAME = 'REDIRECT_REDIRECT_givenName';
    private const SHIB_SURNAME = 'REDIRECT_REDIRECT_sn';
    private const SHIB_EMAIL = 'REDIRECT_REDIRECT_mail';
    private const SHIB_AFFILIATIONS = 'REDIRECT_REDIRECT_PittAffiliate';
    private const SHIB_GROUPS = 'REDIRECT_REDIRECT_PittCustomGroupMembership';

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
     * find method
     *
     * @return \Cake\Http\Response|null|void Redirects to index.
     */
    public function find()
    {
        $options = $this->request->getQueryParams();

        if ($options) {
            $userQuery = $options['q'] ?? '';
            $findType = $options['f'] ?? '';
            $groupQuery = $options['g'] ?? '';
            $statusQuery = $options['s'] ?? '-1';
        } else {
            $userQuery = '';
            $findType = '';
            $groupQuery = '';
            $statusQuery = '-1';
        }

        $BatchGroupsTable = $this->fetchTable('OrcidBatchGroups');
        $batchGroups = $BatchGroupsTable->find('all')->all();

        $StatusTypesTable = $this->fetchTable('OrcidStatusTypes');
        $statusTypes = $StatusTypesTable->find('all')->all();

        $orcidUsers = $this->paginate($this->_parameterize($userQuery, $findType, $groupQuery, $statusQuery));

        $findTypes = ['Containing', 'Starting With', 'Ending With', 'Matching Exactly'];
        $statuses = [$this::NULL_ID => ''];
        $groups = [$this::EMPTY_STRING_ID => ''];

        foreach ($batchGroups as $group) {
            $groups[$group->ID] = $group->NAME;
        }
        asort($groups);

        foreach ($statusTypes as $status) {
            $statuses[$status->ID] = $status->NAME;
        }
        ksort($statuses);

        $groups[$this::NULL_ID] = 'No Matching Group';

        $this->set('findTypes', $findTypes);
        $this->set('userQuery', $userQuery);
        $this->set('selectedType', $findType);
        $this->set('selectedGroup', $groupQuery);
        $this->set('selectedStatus', $statusQuery);
        $this->set('statusTypes', $statuses);
        $this->set('batchGroups', $groups);
        $this->set(compact('orcidUsers'));
    }

    /**
     * report method
     *
     * @param string|null $queryString the query string from previous request
     * @return void
     */
    public function report($queryString = null) {
        parse_str($queryString, $options);

        if ($options) {
            $userQuery = $options['q'] ?? '';
            $findType = $options['f'] ?? '';
            $groupQuery = $options['g'] ?? '';
            $statusQuery = $options['s'] ?? '-1';
        } else {
            $userQuery = '';
            $findType = '';
            $groupQuery = '';
            $statusQuery = '-1';
        }

		$filename = tempnam(TMP, 'rep');

		$fh = fopen($filename, 'w');
		
        $OrcidBatchGroupsTable = $this->fetchTable('OrcidBatchGroups');
		$OrcidStatusTypesTable = $this->fetchTable('OrcidStatusTypes');
		
        $findTypes = [0 => __('Containing'), 1 => __('Starting With'), 2 => __('Ending With'), 3 => __('Matching Exactly')];
		
        $orcidUsers = $this->_parameterize($userQuery, $findType, $groupQuery, $statusQuery);
		
        $reportTitle = __('Users').($userQuery ? $findTypes[$findType].' '.'"'.$userQuery.'"' : '').($groupQuery ? ' '.__('within').' '.$OrcidBatchGroupsTable->get($groupQuery)->NAME : '').($statusQuery != $this::NULL_STRING_ID ? ' '.__('with Current Status of').' '.$OrcidStatusTypesTable->get($statusQuery)->NAME : '');
		
        fputcsv($fh, [$reportTitle,null,null,null,null]);
		fputcsv($fh, ['Username','ORCID iD','Name','RC','Department','Current Status','As Of']);
		
        foreach ($orcidUsers as $orcidUser) {
			fputcsv($fh, [
				$orcidUser->USERNAME,
				$orcidUser->ORCID,
				$orcidUser->displayname,
				$orcidUser->rc,
				$orcidUser->department,
				$OrcidStatusTypesTable->get($orcidUser->current_orcid_statuses[0]->ORCID_STATUS_TYPE_ID)->NAME,
				$orcidUser->current_orcid_statuses[0]->STATUS_TIMESTAMP->i18nFormat('yyyy-MM-dd HH:mm:ss'),
			]);
		}

		fclose($fh);

		$this->response = $this->response->withFile($filename, ['download' => true, 'name' => 'report.csv']);
		$this->response = $this->response->withType('text/csv');

		return $this->response;
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
            'contain' => ['OrcidEmails', 'OrcidEmails.OrcidBatches', 'CurrentOrcidStatuses', 'CurrentOrcidStatuses.OrcidStatusTypes', 'AllOrcidStatuses.OrcidStatusTypes'],
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
            if ($this->OrcidUsers->save($orcidUser) !== false ) {
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
            if ($this->OrcidUsers->save($orcidUser) !== false ) {
                $this->Flash->success(__('The orcid user has been saved.'));

                return $this->redirect(['action' => 'view', $id]);
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
        $this->OrcidUsers->get($id);
        if ($this->request->is(['post', 'put'])) {
            $OrcidStatusTable = $this->fetchTable('OrcidStatuses');
            $OrcidStatusTypesTable = $this->fetchTable('OrcidStatusTypes');
            $orcidStatusTypeID = $OrcidStatusTypesTable->find()->where(['SEQ' => \App\Model\Table\OrcidStatusTypesTable::OPTOUT_SEQUENCE])->first()->ID;
            $orcidStatuses = $OrcidStatusTable->find()->where(['ORCID_USER_ID' => $id, 'ORCID_STATUS_TYPE_ID' =>  $orcidStatusTypeID])->first();

            if (isset($orcidStatuses)) {
                $this->Flash->error(__('The ORCID User has already opted out.'));
                return $this->redirect(['action' => 'view', $id]);
            }
            $time = FrozenTime::now();
            $data = [
                'ORCID_USER_ID' => $id,
                'ORCID_STATUS_TYPE_ID' => $orcidStatusTypeID,
                'STATUS_TIMESTAMP' => $time
            ];
            $OptOutStatus = $OrcidStatusTable->newEntity($data);
            if ($OrcidStatusTable->save($OptOutStatus) !== false ) {
                $this->Flash->success(__('The ORCID Opt-out has been saved.'));
            } else {
                $this->Flash->error(__('The ORCID Opt-out could not be saved. Please, try again.'));
            }
        }
        return $this->redirect(['action' => 'view', $id]);
    }

    /**
     * public facing optout method
     *
     * @param string|null $id Orcid User id.
     * @return \Cake\Http\Response|null|void Redirects to success page or error page.
     * @throws \Cake\Http\Exception\NotFoundException
     */
    public function publicOptout()
    {
        $key = $this::SHIB_USERNAME;
        $user = null;
        if (array_key_exists($key, $_SERVER)) {
            $shib_user = $_SERVER[$key];
            $username = preg_replace('/@pitt.edu$/i', '', $shib_user);
            $orcidUser = $this->OrcidUsers->find()->select(['ID', 'USERNAME'])->where(['USERNAME' => $username])->first();
            if (!isset($orcidUser)) {
                // Create new orcid User
                $orcidUser = $this->OrcidUsers->newEmptyEntity();
                // Set the username to be the one supplied
                $orcidUser->USERNAME = strtoupper($username);

                // Check to see if we successfully saved the user
                try {
                    $result = $this->OrcidUsers->save($orcidUser);
                    // If we don't need we log that to the log files
                } catch (Exception $e) {
                    // This is a flag so we know to die if it wasn't saved.
                    $result = false;
                    Log::write('error', 'ORCID@PITT: ' . $e->getMessage());
                }
                if ($result === false) {
                    // This setups the error page 
                    $this->Flash->error(__('Could not successfully opt out.'));
                    $this->die_with_error_page("500 ORCID@Pitt Database Error", "optout");
                    // We return false because we should error out completely.
                    return;
                }
            }
            $id = $orcidUser->ID;
            $OrcidStatusTable = $this->fetchTable('OrcidStatuses');
            $OrcidStatusTypesTable = $this->fetchTable('OrcidStatusTypes');
            $orcidStatusTypeID = $OrcidStatusTypesTable->find()->where(['SEQ' => \App\Model\Table\OrcidStatusTypesTable::OPTOUT_SEQUENCE])->first()->ID;
            $orcidStatuses = $OrcidStatusTable->find()->where(['ORCID_USER_ID' => $id, 'ORCID_STATUS_TYPE_ID' =>  $orcidStatusTypeID])->first();

            if (isset($orcidStatuses)) {
                $this->viewBuilder()->setTemplatePath("optout")->setTemplate('success')->setLayout('public');
                return;
            }
            $time = FrozenTime::now();
            $data = [
                'ORCID_USER_ID' => $id,
                'ORCID_STATUS_TYPE_ID' => $orcidStatusTypeID,
                'STATUS_TIMESTAMP' => $time
            ];
            $OptOutStatus = $OrcidStatusTable->newEntity($data);
            if ($OrcidStatusTable->save($OptOutStatus) !== false ) {
                $this->viewBuilder()->setTemplatePath("optout")->setTemplate('success')->setLayout('public');
                return;
            } else {
                $this->Flash->error(__('Could not successfully opt out.'));
                $this->die_with_error_page(__('500 ORCID@Pitt Database Error'), "optout");
                return;
            }
        }
        $this->viewBuilder()->setTemplatePath("optout")->setTemplate('notfound')->setLayout('public');
        return;
    }

    private function _parameterize($userQuery, $findType, $groupQuery, $statusQuery)
    {

        // Initialize Batch Groups Table
        $OrcidBatchGroupsTable = $this->fetchTable("OrcidBatchGroups");

        // container to hold conditions
        $conditions = [];

        // Starting point for query
        $orcidUsersTable = $this->OrcidUsers->find('all')->contain(['CurrentOrcidStatuses', 'CurrentOrcidStatuses.OrcidStatusTypes']);

        // query by string matching
        if (!empty($userQuery)) {
            if ($findType === $this::EXACTLY) {
                $conditions = ['OR' => [['USERNAME' => strtoupper($userQuery)], ['ORCID' => $userQuery]]];
            } elseif ($findType === $this::ENDS_WITH) {
                $conditions = ['OR' => [['USERNAME LIKE' => '%' . strtoupper($userQuery)], ['ORCID LIKE' => '%' . $userQuery]]];
            } elseif ($findType === $this::STARTS_WITH) {
                $conditions = ['OR' => [['USERNAME LIKE' => strtoupper($userQuery) . '%'], ['ORCID LIKE' => $userQuery . '%']]];
            } elseif ($findType === $this::CONTAINS) {
                $conditions = ['OR' => [['USERNAME LIKE' => '%' . strtoupper($userQuery) . '%'], ['ORCID LIKE' => '%' . $userQuery . '%']]];
            }
        }

        // query by group
        if (!empty($groupQuery)) {

            $OrcidBatchGroupsTable->updateCache($groupQuery);
            
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

        //query by current status
        if ($statusQuery != $this::NULL_STRING_ID) {

            $orcidUsersTable = $orcidUsersTable->matching('CurrentOrcidStatuses', function ($q) use ($statusQuery) {
                return $q->where(['CurrentOrcidStatuses.ORCID_STATUS_TYPE_ID' => $statusQuery]);
            });

        }
        
        // if no query specified, return nothing
        if (empty($userQuery) && empty($groupQuery) && $statusQuery === $this::NULL_STRING_ID) {
            
            // no ORCID id should be -1
            $conditions = ['ORCID' => $this::NULL_ID];

        }

        // this is the final query after all conditions
        $orcidUsersQuery = $orcidUsersTable->where($conditions);

        return $orcidUsersQuery;
    }

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        $this->Authentication->allowUnauthenticated(['connect', 'publicOptout']);
        parent::beforeFilter($event);
    }
    
    public function connect() {
        // The different items that we need to connect an user's ORCID Account to our application
        // An array created using the Shibboleth constants, that hold each Shibboleth string.
        $shibbolethKeys = [$this::SHIB_USERNAME, $this::SHIB_GIVEN_NAME, $this::SHIB_SURNAME, $this::SHIB_EMAIL, $this::SHIB_AFFILIATIONS, $this::SHIB_GROUPS];
        $shib_groups = [];
        $shib_affiliations = [];
        // Loop through each Shibboleth key
        foreach ($shibbolethKeys as $key){
            // Check that the key exists as to not try and access an undefined index
            if (array_key_exists($key, $_SERVER)) {
                // Check that the key for the Email from Shibboleth, so as to filter it separately
                if ($key !== $this::SHIB_EMAIL) {
                    // Grab variables from Shibboleth and filter the strings
                    $hold = $_SERVER[$key];
                }
                switch ($key) {
                    case $this::SHIB_USERNAME:
                        // Grab the user, and drop email if it is a part of it.
                        $remote_user = preg_replace('/@pitt.edu$/i', '', $hold);
                        break;
                    case $this::SHIB_GIVEN_NAME:
                        // Set the given name
                        $shib_gn = $hold;
                        break;
                    case $this::SHIB_SURNAME:
                        // set the surname
                        $shib_ln = $hold;
                        break;
                    case $this::SHIB_EMAIL:
                        // Grab and set the email from shibboleth
                        $shib_mail = filter_var($_SERVER[$key], FILTER_SANITIZE_EMAIL);
                        // For the ORCID sandbox, use mailinator URLS
                        if (Configure::read('debug')) {
                            $shib_mail = str_replace('@', '.', $shib_mail).'@mailinator.com';
                        }
                        break;
                    case $this::SHIB_AFFILIATIONS:
                        // break out the affiliations into an array using the explode function
                        $shib_affiliations = explode(';', $hold);
                        break;
                    case $this::SHIB_GROUPS:
                        // break out the groups into an array using the explode function
                        $shib_groups = explode(';', $hold);
                        break;
                }
            }
        }
        // neither are needed past this point
        unset($hold);
        unset($shibbolethKeys);

        // Check that the user is affiliated with Pitt through some form of employment.
        if (in_array('employee', $shib_affiliations, TRUE) || in_array('faculty', $shib_affiliations, TRUE) || in_array('staff', $shib_affiliations, TRUE)) {
            // Allow ORCID to know that.
            $orcid_affiliations = ['employment'];
        } else {
            // No other shibboleth affiliations matter.
            $orcid_affiliations = [];
        }

        // Check if the user is in the FERPA group at Pitt
        if (in_array('FERPA', $shib_groups, TRUE)) {
            // Hold that so we don't expose student protected information to ORCID.
            $orcid_affiliations[] = 'FERPA';
        }

        // The address to the user's ORCID account, used in two templates
        $ORCID_LOGIN = $this->read_oauth_resource("ORCID_LOGIN");

        // Check for ORCID sending us an error message
        if ($this->check_for_errors_from_redirect($remote_user, $orcid_affiliations)) {
            return;
        }

        // Check that the user is returned from ORCID or not
        if (!$this->user_is_returned_from_orcid()) {
            // Check if the user should redirect to ORCID or display a different webpage
            if ($this->check_to_redirect_to_ORCID($remote_user, $ORCID_LOGIN, $orcid_affiliations)) {
                $this->redirect_to_ORCID($shib_gn, $shib_ln, $shib_mail);
                return;
            } else {
                return;
            }
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
        $result = $client->post($this->read_oauth_resource('OAUTH_TOKEN_URL'), [
            'code' => $_GET['code'],
            'grant_type' => 'authorization_code',
            'client_id' => $this->read_oauth_resource('OAUTH_CLIENT_ID'),
            'client_secret' => $this->read_oauth_resource('OAUTH_CLIENT_SECRET'),
            'redirect_uri' => $this->read_oauth_resource('OAUTH_REDIRECT_URI'),
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
            $user->ORCID = $response['orcid'];
            $user->TOKEN = $response['access_token'];
            try {
                $result = $this->OrcidUsers->save($user);
            } catch (Exception $e) {
                $result = false;
                Log::write('error', 'ORCID@PITT: ' . $e->getMessage());
            }
            if ($result === false) {
                $this->die_with_error_page("500 ORCID@Pitt Database Error");
                return;
            }
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
        $result = $client->get($this->read_oauth_resource('OAUTH_API_URL').$orcid.'/record', null, [
            'headers' =>[
                'Content-Type' => 'application/vdn.orcid+xml',
                'Authorization' => 'Bearer ' . $token,
            ],
            'curl' => [
                CURLINFO_HEADER_OUT => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS => 5,
            ]]);
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
        $payloadChildren->{"external-id-url"} = $this->read_oauth_resource("EXTERNAL_WEBHOOK").'?id='.$id;
        $result = $client->post($this->read_oauth_resource('OAUTH_API_URL').$orcid.'/external-identifiers', $payload->asXML(), [
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
        $result = $client->post($this->read_oauth_resource('OAUTH_API_URL').$orcid.'/'.$type, $payload->asXML(), [
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
        } catch (Exception $e) {
            error_log($e->getMessage());
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
            error_log($e->getMessage());
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
    function validate_record($orcid, $token, $user, $affiliations = []) {
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
     * Helper function to generate an error page based on a HTTP error code and message
     * @param string $error The error to be propagated down.
     * @param string $templatePath Optional. The path of the error template to be used.
     *                             Defaults to Error.
     * @param string $template Optional. The name of the template to be used,
     *                         Defaults to orciderror.
     */
    function die_with_error_page($error, $templatePath = "Error", $template = "orciderror") {
        $this->viewBuilder()->setTemplatePath($templatePath)->setTemplate($template)->setLayout('public')->setVar('error', $error);
    }

    /**
     * helper function to easily return the data from the config file for OAUTH resources.
     * 
     * @param string $resourceName The specific resource name that we want to get
     * @return string the information that was pulled from the config file for that OAUTH resource.
     */
    function read_oauth_resource($resourceName) {
        $OAUTH_PATH = "Resources.".(Configure::read("debug") ? "default-orcid" : "production-orcid").".";
        return Configure::readOrFail($OAUTH_PATH.$resourceName);
    }

    /**
     * Logic to check for errors when being redirected back from ORCID when connecting an account
     * If an error exists, the function updates the Viewbuilder to display an error
     * For an access denied error, then the user's record is validated and updates the Token for that user
     * 
     * @param string $remote_user The username to check for an account with.
     * @param string[] $orcid_affiliations The affiliations that orcid cares about for this user.
     * @return bool true if an error was found, false if no errors were found
     */
    function check_for_errors_from_redirect(
        string $remote_user,
        array $orcid_affiliations = []
        ): bool {
        if (isset($_GET['error'])) {
            switch ($_GET['error']) {
                case 'access_denied':
                    // user explicitly denied us access (maybe)
                    // ORCID's workflow is a little off - a user can click deny without actually logging in
                    // Clear the existing token if we've lost permission
                    $user = $this->OrcidUsers->find("all")->where(["USERNAME" => strtoupper($remote_user)])->first();
                    if (isset($user)) {
                        // Yes, the user exists.  Do we have both the ORCID ID and the ORCID Token
                        if (isset($user->ORCID) && isset($user->TOKEN)) {
                            // Yes, we have both but are they valid
                            if (!$this->validate_record($user->ORCID, $user->TOKEN, $remote_user, $orcid_affiliations)) {
                                // Yes they are valid. We need to remove the Token from the user
                                $user->Token = '';
                                $this->OrcidUsers->save($user);
                            }
                        }
                    }
                    // Ask if the user meant to do that
                    $this->viewBuilder()->setTemplatePath("connect")->setTemplate('untrusted')->setLayout('public');
                    return true;
                default:
                    // ORCID could send a different error message, but that isn't handled (yet)
                    $this->die_with_error_page('500 Unrecognized ORCID error');
                    return true;
            }
        }
        // We have no errors, so we will continue on in the connect function
        return false;
    }

    /**
     * helper function to easily check if we have not redirected yet.
     * 
     * @return boolean true if we have returned from orcid, false if we haven't
     */
    function user_is_returned_from_orcid(): bool {
        // After the redirect, we will have a 'code' within the GET global variable. 
        // So for it to not have redirected yet, then we need to check that it is missing.
        return isset($_GET['code']);
    }

    /**
     * Helper function to check if we should redirect to ORCID or not.
     * Even if we haven't redirected, there could be an error with the user so we should die.
     * The user already successfully linked their ORCID account so no need to redirect to ORCID.
     * IF neither of the above happen, then we should redirect to ORCID.
     * 
     * @param string $remote_user The username we have gotten from Shibboleth
     * @param string $ORCID_LOGIN The address to the user's ORCID account 
     * @param string[] $orcid_affiliations The affiliations that ORCID cares about for the user
     * 
     * @return bool 
     */
    function check_to_redirect_to_ORCID(
        string $remote_user, 
        string $ORCID_LOGIN,
        array $orcid_affiliations
        ): bool {
        // If we don't have a CODE from ORCID,
        // We are in the workflow before the redirect to ORCID
        // Check the status of the current user
        // Possible outcomes of this conditional are:
        //   An HTTP error message
        //   A redirect to the success message
        //   A pass through to the sendoff to ORCID

        // Does this user exist?
        $user = $this->OrcidUsers->find("all")
        ->where(["USERNAME" => strtoupper($remote_user)])
        ->first();
        if (isset($user) && isset($user->USERNAME)) {
            // Yes, the user exists.  Do we already have both an ORCID and token?
            if (isset($user->ORCID) && isset($user->TOKEN)) {
                // Yes we have both, are they valid?
                if ($this->validate_record($user->ORCID, $user->TOKEN, $remote_user, $orcid_affiliations)) {
                    // Yes, we already have a valid ORCID and token.  We want to display the success message to the end user.
                    $this->viewBuilder()->setTemplatePath("connect")->setTemplate('success')->setLayout('public')->setVar("user", $user)->setVar("ORCID_LOGIN", $ORCID_LOGIN);
                    // We do not want to redirect to ORCID, so return false
                    return false;
                }
            }
        } else {
            // This user doesn't exist yet.  Add the user to the database.

            // Setup a new orcid user
            $user = $this->OrcidUsers->newEmptyEntity();
            // Set the username to be the one supplied
            $user->USERNAME = strtoupper($remote_user);

            // Check to see if we successfully saved the user
            try {
                $result = $this->OrcidUsers->save($user);
                // If we don't need we log that to the log files
            } catch (Exception $e) {
                // This is a flag so we know to die if it wasn't saved.
                $result = false;
                Log::write('error', 'ORCID@PITT: ' . $e->getMessage());
            }
            if ($result === false) {
                // This setups the error page
                $this->die_with_error_page("500 ORCID@Pitt Database Error");
                // We return false because we should error out completely.
                return false;
            }
        }

        // If we haven't exited to this point, note that the user has visited and we are going to redirect them to ORCID
        
        // Get the ORCID Statuses table
        $OrcidStatuses = $this->fetchTable('OrcidStatuses');

        /**
         *  This query is getting all IDs (primary key) out of ORCID Statuses
         *  where the ORCID_STATUS_TYPE_ID matches "a.IDs"
         * "a.IDs" is the ORCID Status Type ID from an inner join between
         * ORCID Users and ORCID Status Types. It is joined on where the 
         * ORCID Users username being equal to the remote_user (which is a username from Shibboleth)
         * and where the ORCID Status Types' sequence is equal to 2.
         * Sequence 2 is when the user has been emailed, but not gone through the ORCID process 
         */
        $orcidStatusQuery = $OrcidStatuses->find('all')
        ->select('ID')
        ->where(function (QueryExpression $exp, Query $q) {
            return $exp->equalFields('OrcidStatuses.ORCID_STATUS_TYPE_ID', 'a.ID');
        })->andWhere(function (QueryExpression $exp, Query $q) {
            return $exp->equalFields('OrcidStatuses.ORCID_USER_ID', 'OrcidUsers.ID');
        });

        /** We are finding all ORCID users inner joined with the ORCID Status Types 
         *  The join is on where the ORCID users username is equal to the remote_user
         *  and where the ORCID Status Types Sequence is 2. (Look above for clarity on both.)
         *  where the user does not already have an ORCID Status for those conditions from above.
        */
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

        // We want to get that user out of the query.
        $newUser = $user->first();

        // Check to see if that user exists, skips if that user already has this status
        if (isset($newUser)) {

            // Since it does, then we need to add a new status for Sequence 2 for that user.

            // Create an empty ORCID Status
            $newStatus = $OrcidStatuses->newEmptyEntity();
            // Set the ORCID status's user id to be the ID of the user we got back.
            $newStatus->ORCID_USER_ID = $newUser->ORCID_USER_ID;
            // Set the ORCID status's status type id to be the ID of the status type we got back
            $newStatus->ORCID_STATUS_TYPE_ID = $newUser->ORCID_STATUS_TYPE_ID;
            // Set the status timestamp to now
            $newStatus->STATUS_TIMESTAMP = FrozenTime::now();

            // Check to see if we successfully saved the status.
            try {
                $result = $OrcidStatuses->save($newStatus);
                // If we don't need we log that to the log files
            } catch (Exception $e) {
                // This is a flag so we know to die if it wasn't saved.
                $result = false;
                Log::write('error', 'ORCID@PITT: ' . $e->getMessage());
            }
            if ($result === false) {
                // This setups the error page
                $this->die_with_error_page("500 ORCID@Pitt Database Error");
                // We return false because we should error out completely.
                return false;
            }
        }

        return true;
    }

    /**
     * helper function to redirect to ORCID
     * 
     * @param string $shib_gn The user's given name that we are passing to ORCID
     * @param string $shib_ln The user's surname that we are passing to ORCID
     * @param string $shib_mail The user's email that we are passing to ORCID
     */
    function redirect_to_ORCID(
        string $shib_gn, 
        string $shib_ln, 
        string $shib_mail
        ): void {
        $state = bin2hex(openssl_random_pseudo_bytes(16));
        setcookie('oauth_state', $state, [
            'expires' => (time() + 3600),
            'httponly' => true,
            'path' => '/'
        ]);
        $url = $this->read_oauth_resource("OAUTH_AUTHORIZATION_URL") . '?' . http_build_query([
            'response_type' => 'code',
            'client_id' => $this->read_oauth_resource("OAUTH_CLIENT_ID"),
            'redirect_uri' => $this->read_oauth_resource("OAUTH_REDIRECT_URI"),
            'scope' => Configure::read("Resources.OAUTH_SCOPE"),
            'state' => $state,
            'given_names' => $shib_gn,
            'family_names' => $shib_ln,
            'email' => $shib_mail,
            // ORCID bug: https://trello.com/c/Y0dqjqId/362-authorization-code-not-generated-when-signed-in-user-visits-link-with-orcid-parameter
            // ULS ticket: https://ulstracker.atlassian.net/browse/SYSDEV-1615
            'orcid' => isset($row['ORCID']) ? $row['ORCID'] : '',
        ]);
        $this->redirect($url);
    }

}
