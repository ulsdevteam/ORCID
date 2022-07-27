<?php 
namespace App\Policy;

use Authorization\Policy\RequestPolicyInterface;
use Cake\Http\ServerRequest;

class RequestPolicy implements RequestPolicyInterface
{
    /**
     * Method to check if the request can be accessed
     *
     * @param \Authorization\IdentityInterface|null $identity Identity
     * @param \Cake\Http\ServerRequest $request Server Request
     * @return bool
     */
    public function canAccess($identity, ServerRequest $request)
    {
        if ($request->getParam('prefix') === 'Admin' && !isset($identity)
        ) {
            return false;
        }

        if (($request->getParam('pass') !== null) && !isset($identity)) {
            return false;
        }

        return true;
    }
}