<?php

namespace OpenProvider\WhmcsRegistrar\Controllers\System;

use OpenProvider\API\JsonAPI;
use OpenProvider\OpenProvider;
use OpenProvider\WhmcsRegistrar\src\Configuration;
use WHMCS\Carbon;
use OpenProvider\WhmcsHelpers\Activity;
use WeDevelopCoffee\wPower\Core\Core;
use OpenProvider\API\API;
use OpenProvider\API\Domain as api_domain;
use WeDevelopCoffee\wPower\Controllers\BaseController;
use WeDevelopCoffee\wPower\Models\Domain;
use WeDevelopCoffee\wPower\Models\Registrar;

/**
 * Class TransferSyncController
 * @package OpenProvider\WhmcsRegistrar\Controllers\System
 */
class DomainSyncController extends BaseController
{
    /**
     * @var API
     */
    private $API;

    /**
     * @var api_domain
     */
    private $api_domain;
    /**
     * @var OpenProvider
     */
    private $openprovider;
    /**
     * @var Domain
     */
    private $domain;

    /**
     * ConfigController constructor.
     */
    public function __construct(Core $core, api_domain $api_domain, Domain $domain)
    {
        parent::__construct($core);

        $this->api_domain   = $api_domain;
        $this->openprovider = new OpenProvider();
        $this->domain       = $domain;
    }

    /**
     * Synchronise the transfer status.
     *
     * @param $params
     * @return array
     */
    public function sync($params)
    {
        $api = $this->openprovider->getApi();

        // Check if the native synchronisation feature
        if (Configuration::getOrDefault('syncUseNativeWHMCS', false) == false) {
            $domain = Domain::find($params['domainid']);
            return array(
                'expirydate'      => $domain->expirydate, // Format: YYYY-MM-DD
                'active'          => true, // Return true if the domain is active
                'expired'         => false, // Return true if the domain has expired
                'transferredAway' => false, // Return true if the domain is transferred out
            );
        }


        $this->domain = $this->domain->find($params['domainid']);

        $setting['syncAutoRenewSetting']         = Configuration::getOrDefault('syncAutoRenewSetting', true);
        $setting['syncIdentityProtectionToggle'] = Configuration::getOrDefault('syncIdentityProtectionToggle', true);

        try {
            // get data from op
            $this->api_domain = $this->openprovider->domain($this->domain->domain);
            $op_domain_result = $api->getDomainRequest($this->api_domain);
            $expiration_date  = Carbon::createFromFormat('Y-m-d H:i:s', $op_domain_result['expirationDate'], 'Europe/Amsterdam');

            if ($op_domain_result['status'] == 'ACT') {
                // auto renew on or not? -> WHMCS is leading.
                if ($setting['syncAutoRenewSetting'] == true)
                    $this->process_auto_renew($op_domain_result);

                // Identity protection or not? -> WHMCS is leading.
                if ($setting['syncIdentityProtectionToggle'] == true)
                    $this->process_identity_protection($op_domain_result, $params);

                return array(
                    'expirydate'      => $this->domain->expirydate, // Format: YYYY-MM-DD
                    'active'          => true, // Return true if the domain is active
                    'expired'         => false, // Return true if the domain has expired
                    'transferredAway' => false, // Return true if the domain is transferred out
                );
            }

            return array();
        } catch (\Exception $ex) {
            if ($ex->getMessage() == 'This action is prohibitted for current domain status.') {
                // Set the status to expired.
                return array(
                    'expirydate'      => $expiration_date, // Format: YYYY-MM-DD
                    'active'          => false, // Return true if the domain is active
                    'expired'         => true, // Return true if the domain has expired
                    'transferredAway' => false, // Return true if the domain is transferred out
                );
            } else if ($ex->getMessage() == 'The domain is not in your account; please transfer it to your account first.') {
                // Set the status to expired.
                return array(
                    'expirydate'      => $this->domain->expirydate, // Format: YYYY-MM-DD
                    'active'          => false, // Return true if the domain is active
                    'expired'         => false, // Return true if the domain has expired
                    'transferredAway' => true, // Return true if the domain is transferred out
                );
            } else {
                return array
                (
                    'error' => $ex->getMessage()
                );
            }
        }

        return [];
    }

    /**
     * Process the Domain autorenew setting
     *
     * @return void
     **/
    private function process_auto_renew($op_domain_result)
    {
        $result = $this->openprovider->toggle_autorenew($this->domain, $op_domain_result);

        if ($result != 'correct') {
            /**
             * Log the activity data
             */
            $activity_data = [
                'id'          => $this->domain->id,
                'domain'      => $this->domain->domain,
                'old_setting' => $result['old_setting'],
                'new_setting' => $result['new_setting'],
            ];

            Activity::log('update_autorenew_setting', $activity_data);
        }
    }

    /**
     * Process the Domain identity protection setting
     *
     * @param $op_domain_result
     * @param $params
     * @return void
     */
    private function process_identity_protection($op_domain_result, $params)
    {
        try {
            $result = $this->openprovider->toggle_whois_protection($this->domain, $op_domain_result);

        } catch (\Exception $e) {
            \logModuleCall('OpenProvider', 'Save identity toggle', $this->domain->domain, [$this->domain->domain, @$op_domain_result], $e->getMessage(), [$params['Password']]);

            $this->unsigned_wpp_contract_domains[] = $this->domain->domain;
        }

        if ($result != 'correct') {
            /**
             * Log the activity data
             */
            $activity_data = [
                'id'          => $this->domain->id,
                'domain'      => $this->domain->domain,
                'old_setting' => $result['old_setting'],
                'new_setting' => $result['new_setting'],
            ];

            Activity::log('update_identity_protection_setting', $activity_data);
        }
    }
}