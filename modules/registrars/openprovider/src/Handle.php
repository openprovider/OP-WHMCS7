<?php
namespace OpenProvider\WhmcsRegistrar\src;

use OpenProvider\API\Customer;
use OpenProvider\WhmcsRegistrar\Models\Tld;
use WeDevelopCoffee\wPower\Models\Domain;
use WeDevelopCoffee\wPower\Handles\Models\Handle as ModelHandle;

/**
 * Handle
 * OpenProvider Registrar module
 *
 * @copyright Copyright (c) Openprovider 2018
 */
class Handle
{
    /**
     * The model
     *
     * @var object \WeDevelopCoffee\wPower\Models\Handle
     */
    protected $model;

    /**
     * Customer object
     *
     * @var object \OpenProvider\API\Customer
     */
    protected $customer;

    /**
     * OpenProvider API
     *
     * @var object \OpenProvider\API\API
     */
    protected $api;

    /**
     * Additional data for handles
     *
     * @var array
     */
    protected $extensionAdditionalData;

    /**
     * Data for customer.
     *
     * @var array
     */
    protected $customerData;

    /**
     * Additional data for customer.
     *
     * @var array
     */
    protected $customerAdditionalData;

    /**
     * Constructor
     *
     * @return void
     */
    public function  __construct (ModelHandle $handle)
    {
        $this->model = $handle;
    }

    /**
     * Find of create the handle.
     *
     * @return string $handle
     */
    public function findOrCreate ($params, $type = 'all')
    {
        $this->prepareHandle($params, $type);

        $foundHandle = $this->model->findExisting();

        if($foundHandle != false && !$this->checkIfHandleStillExistsJson($foundHandle))
        {
            // Detach all domains.
            $foundHandle->domains()->detach();

            // Delete handle.
            $foundHandle->delete();

            // Unset found handle.
            $foundHandle = false;
        }

        // Check if we have found a valid handle.
        if($foundHandle == true)
        {
            // Check if the custom data matches.
            if($this->checkIfAdditionalDataMatches($foundHandle)) {
                // Additional data did match.
                $this->model = $foundHandle;
                $this->model->type = $type;
                $this->model->saveWithDomain($params['domainid'], $type);
            }
            else
                // A handle was found; but the additional data did not match.
                $this->create($params, $type);
        }
        else
        {
            $this->create($params, $type);
        }

        return $this->model->handle;
    }

    /**
     * Check if the handle matches with the additional data.
     * @param $foundHandle
     * @return bool
     */
    public function checkIfAdditionalDataMatches($foundHandle)
    {
        $foundCustomer = $foundHandle->data;

        if($this->customer->extensionAdditionalData != $foundCustomer->extensionAdditionalData)
            exit('no similar extensions');

        if(is_array($this->customerData) && !empty($this->customerData))
        {
            foreach($this->customerData as $customerDataKey => $customerDataValue)
            {
                if($foundCustomer->$customerDataKey != $customerDataValue)
                    return false;
            }
        }

        if(is_array($this->customerAdditionalData) && !empty($this->customerAdditionalData))
        {
            foreach($this->customerAdditionalData as $customerAdditionalDataKey => $customerAdditionalDataValue)
            {
                if($foundCustomer->additionalData->$customerAdditionalDataKey != $customerAdditionalDataValue)
                    return false;
            }
        }

        return true;
    }

    /**
     * Update handle (if possible) or create a new handle.
     *
     * @param array $params
     * @return string handle id
     */
    public function updateOrCreate($params, $type = 'registrant')
    {
        // Find the handles. We don't catch any exception as WHMCS validates
        // if the domain exists.
        $domain         = Domain::find($params['domainid']);

        $params['userid']   = $domain->userid;

        try
        {
            $this->model    = $domain->handles()->wherePivot('type', $type)->firstOrFail();

            // No domain found with this handle, let's continue
            $this->prepareHandle($params, $type);

            $action = $this->findChanges($params);

            if($action == false)
            {
                return $this->model->handle;
            }

            if($action == 'update')
            {
                try
                {
                    // Check if the handle is used for different purposes for the same domain.

                    $domains = $this->model->domains()
                        ->wherePivot('type', '!=', $type)->firstOrFail();

                    // It is, let's create a new handle.
                    $action = 'create';
                } catch (\Exception $e)
                {
                    // It is not, let's update!
                    $action = 'update';
                }
            }

        } catch ( \Exception $e )
        {
            $action = 'create';
        }

        if($action == 'create')
            $this->findOrCreate($params, $type);
        else
            $this->update($params);

        return $this->model->handle;
    }

    /**
     * Set the additional fields for the handle.
     *
     * @param array $fields
     * @return object $this
     */
    public function setExtensionAdditionalData ($fields)
    {
        if(!empty($fields))
        {
            $this->extensionAdditionalData = $fields;
        }

        return $this;
    }

    /**
     * Set the additional customer data.
     *
     * @param array $fields
     * @return object $this
     */
    public function setCustomerData ($fields)
    {
        if(!empty($fields))
        {
            $this->customerData = $fields;
        }

        return $this;
    }

    /**
     * Set the additional customer data.
     *
     * @param array $fields
     * @return object $this
     */
    public function setCustomerAdditionalData ($fields)
    {
        if(!empty($fields))
        {
            $this->customerAdditionalData = $fields;
        }

        return $this;
    }

    /**
     * Prepare the handle.
     *
     * @return void
     */
    protected function prepareHandle ($params, $type)
    {
        $this->customer             = new Customer($params, $type);
        $this->customer->extensionAdditionalData = $this->extensionAdditionalData;

        // Check if tld need short state
        if (isset($params['tld'])) {
            $domainTld = new Tld($params['tld']);
            if ($domainTld->isNeededShortState()) {
                $this->customer->setAddressStateShort();
            }
        }

        if(is_array($this->customerData) && !empty($this->customerData))
        {
            foreach($this->customerData as $customerDataKey => $customerDataValue)
            {
                $this->customer->$customerDataKey = $customerDataValue;
            }
        }

        if(is_array($this->customerAdditionalData) && !empty($this->customerAdditionalData))
        {
            foreach($this->customerAdditionalData as $customerAdditionalDataKey => $customerAdditionalDataValue)
            {
                $this->customer->additionalData->set($customerAdditionalDataKey, $customerAdditionalDataValue);
            }
        }

        $this->model->registrar     = 'openprovider';
        $this->model->user_id       = $params['userid'];
        $this->model->type          = $type;
        $this->model->data          = $this->customer;

        return $this;
    }

    /**
     * Check if the model still exists with OpenProvider.
     *
     * @param ModelHandle $model
     * @return bool
     */
    public function checkIfHandleStillExistsJson(\WeDevelopCoffee\wPower\Handles\Models\Handle $model)
    {
        try
        {
            $opCustomer = $this->api->getCustomerRequest($model->handle, true);
            return true;
        } catch ( \Exception $e)
        {
            // If the handle does not exist, create a new one.
            return false;
        }
    }

    /**
     * Find the changes between the current and updated data.
     *
     * @return void
     */
    protected function findChanges ($params)
    {
        try
        {
            $opCustomer = $this->api->getCustomerRequest($this->model->handle, true);
        }
        catch ( \Exception $e)
        {
            // If the handle does not exist, create a new one.
            return 'create';
        }

        if(
            $this->customer->companyName == $opCustomer['company_name']  &&
            $this->customer->name->initials == $opCustomer['name']['initials'] &&
            $this->customer->name->firstName == $opCustomer['name']['first_mame'] &&
            $this->customer->name->lastName == $opCustomer['name']['last_name'] &&
            $this->customer->gender == $opCustomer['gender'] &&
            $this->customer->address->street == $opCustomer['address']['street'] &&
            $this->customer->address->number == $opCustomer['address']['number'] &&
            $this->customer->address->city == $opCustomer['address']['city'] &&
            $this->customer->address->state == $opCustomer['address']['state'] &&
            $this->customer->phone->countryCode == $opCustomer['phone']['country_code'] &&
            $this->customer->phone->areaCode == $opCustomer['phone']['area_code'] &&
            $this->customer->phone->subscriberNumber == $opCustomer['phone']['subscriber_number'] &&
            $this->customer->email == $opCustomer['email']
        )
        {
            return false;
        }
        //if name & company name are the same, then call modifyCustomerRequest only
        elseif ($opCustomer['name']['first_name'] == $this->customer->name->firstName &&
            $opCustomer['name']['last_name'] == $this->customer->name->lastName &&
            $opCustomer['company_name'] == $this->customer->companyName &&
            $this->model->isUsedByOtherDomains($params['domainid']) === false)
            return 'update';
        else
            return 'create';
    }

    /**
     * Create handle
     *
     * @return object $handle
     */
    protected function create ($params, $type)
    {
        $result                 = $this->api->createCustomerRequest($this->customer);
        $this->model->id        = '';
        $this->model->exists    = false;
        $this->model->handle    = $result['handle'];
        $this->model->saveWithDomain($params['domainid'], $type);

        return $this;
    }

    /**
     * Update handle
     *
     * @return object $handle
     */
    protected function update ($params)
    {
        $this->customer->handle = $this->model->handle;
        $result                 = $this->api->updateCustomerRequest($this->customer);
        $this->model->data      = $this->customer;
        $this->model->save(['overrideUniqueCheck' => true]);

        return $this;
    }

    /**
     * Set the api object.
     *
     * @param object $api \OpenProvider\API\API
     *
     * @return $this
     */
    public function setApi ($api)
    {
        $this->api = $api;
        return $this;
    }
}


