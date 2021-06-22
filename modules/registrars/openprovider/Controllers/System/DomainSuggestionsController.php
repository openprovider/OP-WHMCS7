<?php

namespace OpenProvider\WhmcsRegistrar\Controllers\System;

use \Exception;

use OpenProvider\API\API;
use OpenProvider\API\ApiInterface;
use OpenProvider\API\Domain;
use OpenProvider\PlacementPlus;

use WeDevelopCoffee\wPower\Controllers\BaseController;
use WeDevelopCoffee\wPower\Core\Core;

use WHMCS\Domains\DomainLookup\ResultsList;
use WHMCS\Domains\DomainLookup\SearchResult;

/**
 * Class GetDomainSuggestions
 * @package OpenProvider\WhmcsRegistrar\Controllers\System
 */
class DomainSuggestionsController extends BaseController
{
    private const SUGGESTION_DOMAIN_NAME_COUNT = 9;

    private const SUGGESTION_DOMAINS_COUNT_FROM_PLACEMENT_PLUS_LIVE = 1;
    private const SUGGESTION_DOMAINS_COUNT_FROM_PLACEMENT_PLUS_CTE = 10;


    /**
     * @var API
     */
    private $API;
    /**
     * @var ResultsList
     */
    private $resultsList;
    /**
     * @var ApiInterface
     */
    private $apiClient;

    /**
     * ConfigController constructor.
     */
    public function __construct(Core $core, Api $API, ApiInterface $apiClient)
    {
        parent::__construct($core);

        $this->API = $API;
        $this->apiClient = $apiClient;

        $this->resultsList = new ResultsList();
    }

    /**
     * @param $params
     * @return ResultsList
     * @throws Exception
     */
    public function get($params)
    {
        $api = $this->API;
        $api->setParams($params);
        $args = [
            'name' => $params['searchTerm'],
            'limit' => self::SUGGESTION_DOMAIN_NAME_COUNT,
        ];

        $suggestionSettings = $params['suggestionSettings'];
        if (isset($suggestionSettings['preferredLanguage']) && !empty($suggestionSettings['preferredLanguage']))
            $args['language'] = $suggestionSettings['preferredLanguage'];

        $args['sensitive'] = isset($suggestionSettings['sensitive']) && $suggestionSettings['sensitive'] == 'on';

        if (isset($suggestionSettings['suggestTlds']) && count($suggestionSettings['suggestTlds']) > 0) {
            $args['tlds'] = array_map(function ($tld) {
                return mb_substr($tld, 1);
            }, explode(',', $suggestionSettings['suggestTlds']));
        }

        $isTestModeEnabled = $params['test_mode'] == 'on';

        if (PlacementPlus::isCredentialExist()) {
            $encodedDomain = urlencode($params['searchTerm']);

            $countSuggestedDomainsFromPlacementPlus = $isTestModeEnabled ?
                self::SUGGESTION_DOMAINS_COUNT_FROM_PLACEMENT_PLUS_CTE :
                self::SUGGESTION_DOMAINS_COUNT_FROM_PLACEMENT_PLUS_LIVE;

            $placementPlusSuggestionDomains = $this->getPlacementPlusSuggestedDomains(
                $encodedDomain,
                $countSuggestedDomainsFromPlacementPlus
            );

            foreach ($placementPlusSuggestionDomains as $domain) {
                if (isset($domain['sld']) && isset($domain['tld'])) {
                    $searchResult = new SearchResult($domain['sld'], $domain['tld']);
                    $this->resultsList->append($searchResult);
                    continue;
                }
                break;
            }
        }

        //get suggested domains
        try {
            $suggestedDomains = $this->apiClient->call('suggestNameDomainRequest', $args)->getData()['results'];
        } catch (Exception $e) {
            return $this->resultsList;
        }

        $domains = [];
        foreach ($suggestedDomains as $item) {
            $domain = new Domain();
            $domain->extension  = $item['tld'];
            $domain->name       = $item['domain'];
            $domains[]          = $domain;
        }

        // check domains availability and append to this->resultsList
        $resultsList = $this->checkDomains($domains, $params);

        foreach ($resultsList as $domain) {
            $this->resultsList->append($domain);
        }

        return $this->resultsList;
    }

    /**
     * method to check domains by 15 per time
     *
     * @param $domains
     * @param $params
     * @return array
     */
    private function checkDomains($domains, $params)
    {
        $result = [];

        $checkedDomainsResponse = $this->apiClient->call('checkDomainRequest', [
            'domains' => $domains
        ]);

        if (!$checkedDomainsResponse->isSuccess()) {
            if($checkedDomainsResponse->getcode() == 307)
            {
                // OP response: "Your domain request contains an invalid extension!""
                // Meaning: the id is not supported.
                foreach($params['tldsToInclude'] as $tld)
                {
                    $domain_tld  = $tld;
                    $domain_sld  = $params['isIdnDomain'] ? $params['punyCodeSearchTerm'] : $params['searchTerm'];
                    $searchResult = new SearchResult($domain_sld, $domain_tld);
                    $searchResult->setStatus(SearchResult::STATUS_TLD_NOT_SUPPORTED);
                    $result[] = $searchResult;
                }
            }
            \logModuleCall('openprovider', 'whois', $domains, $checkedDomainsResponse->getMessage(), null, [$params['Password']]);
            return $result;
        }

        $checkedDomains = $checkedDomainsResponse->getData();
        foreach($checkedDomains['results'] as $domain_status)
        {
            $domain_sld = explode('.', $domain_status['domain'])[0];
            $domain_tld = str_replace($domain_sld . '.', '', $domain_status['domain']);

            $searchResult = new SearchResult($domain_sld, $domain_tld);
            if($params['OpenproviderPremium'] == true && isset($domain_status['premium']) && $domain_status['status'] == 'free')
            {
                $status = SearchResult::STATUS_NOT_REGISTERED;
                $searchResult->setPremiumDomain(true);

                $args['domain']['name']      = $domain_sld;
                $args['domain']['extension'] = $domain_tld;
                $args['operation']           = 'create';

                $createPricingResponse = $this->apiClient->call('retrievePriceDomainRequest', $args);
                if (!$createPricingResponse->isSuccess()) {
                    continue;
                }

                $createPricing = $createPricingResponse->getData();

                $args['operation'] = 'transfer';
                $transferPricingResponse  = $this->apiClient->call('retrievePriceDomainRequest', $args);
                if (!$transferPricingResponse->isSuccess()) {
                    continue;
                }

                $transferPricing = $transferPricingResponse->getData();

                // Retrieve the pricing
                $searchResult->setPremiumCostPricing(
                    array(
                        'register'  => $createPricing['price']['reseller']['price'],
                        'renew'     =>  $transferPricing['price']['reseller']['price'],
                        'CurrencyCode' => $createPricing['price']['reseller']['currency'],
                    )
                );
            }
            elseif($domain_status['status'] == 'free')
                $status = SearchResult::STATUS_NOT_REGISTERED;
            else
                $status = SearchResult::STATUS_REGISTERED;

            $searchResult->setStatus($status);

            $result[] = $searchResult;
        }

        return $result;
    }

    /**
     * @param string $domain
     * @param int $number
     * @return array
     */
    private function getPlacementPlusSuggestedDomains(
        string $domain,
        int $number = 10
    ): array
    {
        $reply = PlacementPlus::getSuggestionDomain($domain);

        $result = [];

        if (!isset($reply['output']['domains'])) {
            return $result;
        }

        $domains = $reply['output']['domains'];
        for ($i = 0; $i < $number; $i++) {
            if (isset($domains[$i])) {
                $result[] = $domains[$i];
                continue;
            }

            break;
        }

        return $result;
    }
}
