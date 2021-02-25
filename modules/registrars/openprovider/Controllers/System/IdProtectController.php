<?php

namespace OpenProvider\WhmcsRegistrar\Controllers\System;

use Exception;
use OpenProvider\WhmcsRegistrar\src\Notification;
use WHMCS\Database\Capsule;
use WeDevelopCoffee\wPower\Controllers\BaseController;
use WeDevelopCoffee\wPower\Core\Core;
use OpenProvider\OpenProvider;
use OpenProvider\API\Domain;

/**
 * Class IdProtect
 * @package OpenProvider\WhmcsRegistrar\Controllers\System
 */
class IdProtectController extends BaseController
{
    /**
     * @var OpenProvider
     */
    private $openProvider;
    /**
     * @var Domain
     */
    private $domain;

    /**
     * ConfigController constructor.
     */
    public function __construct(Core $core, OpenProvider $openProvider, Domain $domain)
    {
        parent::__construct($core);

        $this->openProvider = $openProvider;
        $this->domain       = $domain;
    }

    /**
     * Toggle the id protection.
     *
     * @param $params
     * @return array
     */
    public function toggle($params)
    {
        $params['sld']        = $params['original']['domainObj']->getSecondLevel();
        $params['tld']        = $params['original']['domainObj']->getTopLevel();
        $params['domainname'] = $params['sld'] . '.' . $params['tld'];

        // Get the domain details
        $domain = Capsule::table('tbldomains')
            ->where('id', $params['domainid'])
            ->get()[0];

        if (isset($params['protectenable']))
            $domain->idprotection = $params['protectenable'];

        $api = $this->openProvider->api;
        try {
            $op_domain_obj = $this->openProvider->domain($domain->domain);

            $op_domain = $api->retrieveDomainRequest($op_domain_obj);
            $this->openProvider->toggle_whois_protection($domain, $op_domain);

            return array(
                'success' => 'success',
            );
        } catch (Exception $e) {
            \logModuleCall('OpenProvider', 'Save identity toggle', $params['domainname'], [$this->openProvider->domain, @$op_domain, $OpenProvider], $e->getMessage(), [$params['Password']]);

            if ($e->getMessage() == 'Wpp contract is not signed') {
                $notification = new Notification();
                $notification->WPP_contract_unsigned_one_domain($params['domainname'])
                    ->send_to_admins();

            }

            return array(
                'error' => $e->getMessage(),
            );
        }
    }
}