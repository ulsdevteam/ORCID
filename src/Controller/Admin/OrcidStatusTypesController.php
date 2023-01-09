<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * OrcidStatusTypes Controller
 *
 * @property \App\Model\Table\OrcidStatusTypesTable $OrcidStatusTypes
 * @method \App\Model\Entity\OrcidStatusType[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class OrcidStatusTypesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = ['order' => ['OrcidStatusTypes.seq' => 'asc']];
        $orcidStatusTypes = $this->paginate($this->OrcidStatusTypes);

        $this->set(compact('orcidStatusTypes'));
    }

    /**
     * View method
     *
     * @param string|null $id Orcid Status Type id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $orcidStatusType = $this->OrcidStatusTypes->get($id, [
            'contain' => ['CurrentOrcidStatuses', 'CurrentOrcidStatuses.OrcidUsers']
        ]);

        $this->set(compact('orcidStatusType'));
    }
}
