<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use App\Model\Entity\OrcidStatusType;
use Cake\Http\Exception\NotFoundException;
use Cake\I18n\FrozenTime;
use Cake\Collection\Collection;

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
        $orcidUsersTable = $this->fetchTable('OrcidUsers');
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

        // container to hold condtions
        $conditions = [];

        // Starting point for query
        $orcidUsersTable = $this->fetchTable('OrcidUsers');
        $orcidUsersTable = $orcidUsersTable->find('all')->contain(['CurrentOrcidStatuses', 'CurrentOrcidStatuses.OrcidStatusTypes']);

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
                    return $q->where(['OrcidBatchGroupCaches.ORCID_BATCH_GROUP_ID IS NOT NULL']);
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
            // no oricid id should be -1
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
    
    // The adding of a new orcid user shouldn't be too bad with it being inside the cakephp
    // application The main issue is understanding and rewriting the actual connection.
    public function connect() {
        echo "TEST";
        echo str_contains($_SERVER['REDIRECT_REDIRECT_Shib-Handler'], "pitt");
        /* if(!isset($_SERVER['REDIRECT_REDIRECT_Shib-Handler']) ||  ) {
            error_log('Invalid AUTH_TYPE: '.(isset($_SERVER['AUTH_TYPE']) ? $_SERVER['AUTH_TYPE'] : ''));
            die_with_error_page('403 Unauthenticated');
        } */
        return;

        // Grab the remote user from Shibboleth
        $remote_user = filter_var($_SERVER['REDIRECT_REDIRECT_eppn'], FILTER_SANITIZE_STRING);
        $remote_user = preg_replace('/@pitt.edu$/i', '', $remote_user);
        // Grab variables from Shibboleth
        $shib_gn = filter_var($_SERVER['REDIRECT_REDIRECT_givenName'], FILTER_SANITIZE_STRING);
        //$shib_mn = filter_var(isset($_SERVER['middleName']) ? $_SERVER['middleName'] : ''), FILTER_SANITIZE_STRING);
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
    }

    /**
     * Read an ORCID record, returning XML
     * 
     * @param string $orcid ORCID Id
     * @param string $token ORCID access token
     * @return string XML on success
     */
    function read_profile($orcid, $token) {
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLINFO_HEADER_OUT => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS => 5,
                CURLOPT_URL => OAUTH_API_URL.$orcid.'/record',
                CURLOPT_HTTPHEADER => array('Content-Type: application/vdn.orcid+xml', 'Authorization: Bearer '.$token),
            )
        );
        $result = curl_exec($curl);	 //fetches all the records of a user
        $info = curl_getinfo($curl);
        if ($info['http_code'] == 200) {
            return $result;
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
        $payload = '<?xml version="1.0" encoding="UTF-8"?>
        <external-identifier:external-identifier 
            xmlns:external-identifier="http://www.orcid.org/ns/external-identifier" 
            xmlns:common="http://www.orcid.org/ns/common" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
            xsi:schemaLocation="http://www.orcid.org/ns/external-identifier ../person-external-identifier-2.0.xsd">				
                <common:external-id-type>'.PITT_EXTID_NAME.'</common:external-id-type>
                <common:external-id-value>'.$id.'</common:external-id-value>
                <common:external-id-url>'.EXTERNAL_WEBHOOK.'?id='.$id.'</common:external-id-url>
                <common:external-id-relationship>self</common:external-id-relationship>
        </external-identifier:external-identifier>';
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLINFO_HEADER_OUT => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $payload,
                CURLOPT_URL => OAUTH_API_URL.$orcid.'/external-identifiers',
                CURLOPT_HTTPHEADER => array('Content-Type: application/orcid+xml', 'Content-Length: '.strlen($payload), 'Authorization: Bearer '.$token),
            )
        );
        $result = curl_exec($curl);
        $info = curl_getinfo($curl);
        // why is this code usually 200?
        return ($info['http_code'] == 201 || $info['http_code'] == 200);
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
        $commonParams = '<common:name>University of Pittsburgh</common:name>
                <common:address>
                    <common:city>Pittsburgh</common:city>
                    <common:region>PA</common:region>
                    <common:country>US</common:country>
                </common:address>
                <common:disambiguated-organization>
                    <common:disambiguated-organization-identifier>'.PITT_AFFILIATION_ID.'</common:disambiguated-organization-identifier>
                    <common:disambiguation-source>'.PITT_AFFILIATION_KEY.'</common:disambiguation-source>
                </common:disambiguated-organization>';
            
        if($type == 'employment') {
            $payload = '<?xml version="1.0" encoding="UTF-8"?>
            <employment:employment visibility="public"		  
            xmlns:employment="http://www.orcid.org/ns/employment" xmlns:common="http://www.orcid.org/ns/common"
            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
            xmlns="http://www.orcid.org/ns/orcid"		 
            xsi:schemaLocation="http://www.orcid.org/ns/employment ../employment-2.0.xsd">
            <employment:organization>
                '.$commonParams.'
            </employment:organization>
            </employment:employment>';
        } else {
            $payload = '<?xml version="1.0" encoding="UTF-8"?>
            <education:education visibility="public" 
            xmlns:education="http://www.orcid.org/ns/education"
            xmlns:common="http://www.orcid.org/ns/common"
            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"		 
            xmlns="http://www.orcid.org/ns/orcid"
            xsi:schemaLocation="http://www.orcid.org/ns/education ../education-2.0.xsd">
            <education:organization>
                '.$commonParams.'
            </education:organization>
            </education:education>';
        }
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLINFO_HEADER_OUT => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $payload,
                CURLOPT_URL => OAUTH_API_URL.$orcid.'/'.$type,
                CURLOPT_HTTPHEADER => array('Content-Type: application/vnd.orcid+xml', 'Content-Length: '.strlen($payload), 'Authorization: Bearer '.$token),
            )
        );
        $result = curl_exec($curl);
        $info = curl_getinfo($curl);
        return ($info['http_code'] == 201 || $info['http_code'] == 200);
    }

    /**
     * Check whether the Pitt Affiliation exists in the ORCID profile
     * 
     * @param string $xml
     * @return boolean
     */
    function read_affiliation($xml, $type) {
        try {
            $doc = new DOMDocument();
            $doc->loadXML($xml);
            $xpath = new DOMXPath($doc);
            $xpath->registerNamespace('o', "http://www.orcid.org/ns/activities");
            $xpath->registerNamespace('e', "http://www.orcid.org/ns/".$type);
            $xpath->registerNamespace('c', "http://www.orcid.org/ns/common");
            // Check if disambiguation source exists for education or employment by matching with our disambiguation-source and disambiguation-organization-identifier
            $elements = $xpath->query('//e:'.$type.'-summary[e:organization/c:disambiguated-organization[c:disambiguation-source[text()="'.PITT_AFFILIATION_KEY.'"] and c:disambiguated-organization-identifier[text()="'.PITT_AFFILIATION_ID.'"]]]'); 
        } catch (Exception $e) {
            error_log($e);
            return false;
        }
        return ($elements && $elements->length);
    }


    /**
     * Check whether the Pitt ID exists in the ORCID profile
     * 
     * @param string $xml
     * @return boolean
     */
    function read_extid($xml) {
        try {
            $doc = new DOMDocument();		
            $doc->loadXML($xml);
            $xpath = new DOMXPath($doc);
            $xpath->registerNamespace('o', "http://www.orcid.org/ns/common");
            // Check on an external ID with our common name
            $elements = $xpath->query('//o:external-id-type[text()="'.PITT_EXTID_NAME.'"]');
        } catch (Exception $e) {
            error_log($e);
            return false;
        }
        return ($elements && $elements->length);
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
        $profile = read_profile($orcid, $token);
        if ($profile) {
            // The profile should have an External ID unless the the FERPA flag is present on a student-only record
            if ((!in_array('FERPA', $affiliations) || in_array('employment', $affiliations)) && !read_extid($profile)) {
                if (!write_extid($orcid, $token, $user)) {
                    return false;
                }
            }
            // The profile should have each affiliation, unless the FERPA flag blocks a student affiliation
            // It is up to the caller to only pass the desired affilaitions (e.g. writing employment but not education)
            // write_affiliation will filter only valid ORCID profile affiliations (e.g. ignoring FERPA as a key)
            foreach ($affiliations as $affiliation) {
                if ($affiliation == 'education' && in_array('FERPA', $affiliations)) {
                    continue;
                }
                if (!read_affiliation($profile, $affiliation)) {
                    if (!write_affiliation($orcid, $token, $affiliation)) {
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
        header(SERVER_PROTOCOL.' '.$error);
        $html = array(
            'header' => 'ORCID@Pitt Problem',
            'p' => array('Our apologies. Something went wrong and we were unable to create an ORCID iD for you and link it to the University of Pittsburgh.', 'Please <a href="/connect">try again</a>.', 'If you need assistance with creating your ORCID iD, please contact the ORCID Communications Group (<a href="mailto:orcidcomm@mail.pitt.edu">orcidcomm@mail.pitt.edu</a>).', 'Thank you for your patience.'),
            'error' => array($error.' - '.date("Y-m-d H:i:s")),
        );
        require('template.php');
        exit();
    }

    /**
     * Execute an SQL query or die trying
     * 
     * @param object $conn Oracle SQL connection
     * @param string $sql query
     * @param array $binder keyed array of bind variables
     * @return mixed boolean true (UPDATE, DELETE, INSERT) or associative array (SELECT) if successful
     */
    function execute_query_or_die($conn, $sql, $binder) {
        $stmt = oci_parse($conn, $sql);
        if ($stmt) {
            foreach ($binder as $k => $v) {
                if (!oci_bind_by_name($stmt, ':'.$k, $binder[$k])) {
                    error_log(var_export(oci_error(), true));
                    die_with_error_page('500 Database connection error');
                }
            }
            if (oci_execute($stmt)) {
                if (preg_match('/^SELECT/i', $sql)) {
                    $rs = oci_fetch_array($stmt, OCI_ASSOC);
                    if (is_array($rs)) {
                        return $rs;
                    } else {
                        return array();
                    }
                }
            } else {
                error_log(var_export(oci_error(), true));
                die_with_error_page('500 Database connection error');
            }
        } else {
            error_log(var_export(oci_error(), true));
            die_with_error_page('500 Database connection error');
        }
        return true;
    }
}
