<?php
/**
 * OrderSslOrder
 *
 * PHP version 5
 *
 * @category Class
 * @package  Openprovider\Api\Rest\Client\Ssl
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */

/**
 * SSL
 *
 * No description provided (generated by Openapi Generator https://github.com/openapitools/openapi-generator)
 *
 * The version of the OpenAPI document: 1.0.0-beta
 * 
 * Generated by: https://openapi-generator.tech
 * OpenAPI Generator version: 4.0.3
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace Openprovider\Api\Rest\Client\Ssl\Model;

use \ArrayAccess;
use Openprovider\Api\Rest\Client\Base\ObjectSerializer;
use Openprovider\Api\Rest\Client\Base\ModelInterface;

/**
 * OrderSslOrder Class Doc Comment
 *
 * @category Class
 * @package  Openprovider\Api\Rest\Client\Ssl
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */
class OrderSslOrder implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'orderSslOrder';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'active_date' => 'string',
        'additional_data' => '\Openprovider\Api\Rest\Client\Ssl\Model\OrderSslOrderAdditionalData[]',
        'administrative_handle' => 'string',
        'autorenew' => 'string',
        'billing_handle' => 'string',
        'brand_name' => 'string',
        'certificate' => 'string',
        'common_name' => 'string',
        'csr' => 'string',
        'domain_validation_methods' => '\Openprovider\Api\Rest\Client\Ssl\Model\OrderSslOrderDomainValidationMethods[]',
        'domain_validation_statuses' => '\Openprovider\Api\Rest\Client\Ssl\Model\OrderSslOrderDomainValidationStatuses',
        'email_approver' => 'string',
        'email_reissue' => 'string',
        'expiration_date' => 'string',
        'features' => 'string',
        'host_names' => 'string[]',
        'id' => 'int',
        'intermediate_certificate' => 'string',
        'options' => '\Openprovider\Api\Rest\Client\Ssl\Model\OrderSslOrderOptions',
        'order_date' => 'string',
        'organization_handle' => 'string',
        'period' => 'int',
        'product_id' => 'int',
        'product_name' => 'string',
        'root_certificate' => 'string',
        'software' => 'string',
        'sslinhva_order_id' => 'string',
        'status' => 'string',
        'technical_handle' => 'string',
        'validation_method' => 'string',
        'vendor_order_id' => 'string',
        'vendor_reference_id' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPIFormats = [
        'active_date' => null,
        'additional_data' => null,
        'administrative_handle' => null,
        'autorenew' => null,
        'billing_handle' => null,
        'brand_name' => null,
        'certificate' => null,
        'common_name' => null,
        'csr' => null,
        'domain_validation_methods' => null,
        'domain_validation_statuses' => null,
        'email_approver' => null,
        'email_reissue' => null,
        'expiration_date' => null,
        'features' => null,
        'host_names' => null,
        'id' => 'int32',
        'intermediate_certificate' => null,
        'options' => null,
        'order_date' => null,
        'organization_handle' => null,
        'period' => 'int32',
        'product_id' => 'int32',
        'product_name' => null,
        'root_certificate' => null,
        'software' => null,
        'sslinhva_order_id' => null,
        'status' => null,
        'technical_handle' => null,
        'validation_method' => null,
        'vendor_order_id' => null,
        'vendor_reference_id' => null
    ];

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function openAPITypes()
    {
        return self::$openAPITypes;
    }

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function openAPIFormats()
    {
        return self::$openAPIFormats;
    }

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'active_date' => 'active_date',
        'additional_data' => 'additional_data',
        'administrative_handle' => 'administrative_handle',
        'autorenew' => 'autorenew',
        'billing_handle' => 'billing_handle',
        'brand_name' => 'brand_name',
        'certificate' => 'certificate',
        'common_name' => 'common_name',
        'csr' => 'csr',
        'domain_validation_methods' => 'domain_validation_methods',
        'domain_validation_statuses' => 'domain_validation_statuses',
        'email_approver' => 'email_approver',
        'email_reissue' => 'email_reissue',
        'expiration_date' => 'expiration_date',
        'features' => 'features',
        'host_names' => 'host_names',
        'id' => 'id',
        'intermediate_certificate' => 'intermediate_certificate',
        'options' => 'options',
        'order_date' => 'order_date',
        'organization_handle' => 'organization_handle',
        'period' => 'period',
        'product_id' => 'product_id',
        'product_name' => 'product_name',
        'root_certificate' => 'root_certificate',
        'software' => 'software',
        'sslinhva_order_id' => 'sslinhva_order_id',
        'status' => 'status',
        'technical_handle' => 'technical_handle',
        'validation_method' => 'validation_method',
        'vendor_order_id' => 'vendor_order_id',
        'vendor_reference_id' => 'vendor_reference_id'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'active_date' => 'setActiveDate',
        'additional_data' => 'setAdditionalData',
        'administrative_handle' => 'setAdministrativeHandle',
        'autorenew' => 'setAutorenew',
        'billing_handle' => 'setBillingHandle',
        'brand_name' => 'setBrandName',
        'certificate' => 'setCertificate',
        'common_name' => 'setCommonName',
        'csr' => 'setCsr',
        'domain_validation_methods' => 'setDomainValidationMethods',
        'domain_validation_statuses' => 'setDomainValidationStatuses',
        'email_approver' => 'setEmailApprover',
        'email_reissue' => 'setEmailReissue',
        'expiration_date' => 'setExpirationDate',
        'features' => 'setFeatures',
        'host_names' => 'setHostNames',
        'id' => 'setId',
        'intermediate_certificate' => 'setIntermediateCertificate',
        'options' => 'setOptions',
        'order_date' => 'setOrderDate',
        'organization_handle' => 'setOrganizationHandle',
        'period' => 'setPeriod',
        'product_id' => 'setProductId',
        'product_name' => 'setProductName',
        'root_certificate' => 'setRootCertificate',
        'software' => 'setSoftware',
        'sslinhva_order_id' => 'setSslinhvaOrderId',
        'status' => 'setStatus',
        'technical_handle' => 'setTechnicalHandle',
        'validation_method' => 'setValidationMethod',
        'vendor_order_id' => 'setVendorOrderId',
        'vendor_reference_id' => 'setVendorReferenceId'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'active_date' => 'getActiveDate',
        'additional_data' => 'getAdditionalData',
        'administrative_handle' => 'getAdministrativeHandle',
        'autorenew' => 'getAutorenew',
        'billing_handle' => 'getBillingHandle',
        'brand_name' => 'getBrandName',
        'certificate' => 'getCertificate',
        'common_name' => 'getCommonName',
        'csr' => 'getCsr',
        'domain_validation_methods' => 'getDomainValidationMethods',
        'domain_validation_statuses' => 'getDomainValidationStatuses',
        'email_approver' => 'getEmailApprover',
        'email_reissue' => 'getEmailReissue',
        'expiration_date' => 'getExpirationDate',
        'features' => 'getFeatures',
        'host_names' => 'getHostNames',
        'id' => 'getId',
        'intermediate_certificate' => 'getIntermediateCertificate',
        'options' => 'getOptions',
        'order_date' => 'getOrderDate',
        'organization_handle' => 'getOrganizationHandle',
        'period' => 'getPeriod',
        'product_id' => 'getProductId',
        'product_name' => 'getProductName',
        'root_certificate' => 'getRootCertificate',
        'software' => 'getSoftware',
        'sslinhva_order_id' => 'getSslinhvaOrderId',
        'status' => 'getStatus',
        'technical_handle' => 'getTechnicalHandle',
        'validation_method' => 'getValidationMethod',
        'vendor_order_id' => 'getVendorOrderId',
        'vendor_reference_id' => 'getVendorReferenceId'
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @return array
     */
    public static function attributeMap()
    {
        return self::$attributeMap;
    }

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @return array
     */
    public static function setters()
    {
        return self::$setters;
    }

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @return array
     */
    public static function getters()
    {
        return self::$getters;
    }

    /**
     * The original name of the model.
     *
     * @return string
     */
    public function getModelName()
    {
        return self::$openAPIModelName;
    }

    

    

    /**
     * Associative array for storing property values
     *
     * @var mixed[]
     */
    protected $container = [];

    /**
     * Constructor
     *
     * @param mixed[] $data Associated array of property values
     *                      initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->container['active_date'] = isset($data['active_date']) ? $data['active_date'] : null;
        $this->container['additional_data'] = isset($data['additional_data']) ? $data['additional_data'] : null;
        $this->container['administrative_handle'] = isset($data['administrative_handle']) ? $data['administrative_handle'] : null;
        $this->container['autorenew'] = isset($data['autorenew']) ? $data['autorenew'] : null;
        $this->container['billing_handle'] = isset($data['billing_handle']) ? $data['billing_handle'] : null;
        $this->container['brand_name'] = isset($data['brand_name']) ? $data['brand_name'] : null;
        $this->container['certificate'] = isset($data['certificate']) ? $data['certificate'] : null;
        $this->container['common_name'] = isset($data['common_name']) ? $data['common_name'] : null;
        $this->container['csr'] = isset($data['csr']) ? $data['csr'] : null;
        $this->container['domain_validation_methods'] = isset($data['domain_validation_methods']) ? $data['domain_validation_methods'] : null;
        $this->container['domain_validation_statuses'] = isset($data['domain_validation_statuses']) ? $data['domain_validation_statuses'] : null;
        $this->container['email_approver'] = isset($data['email_approver']) ? $data['email_approver'] : null;
        $this->container['email_reissue'] = isset($data['email_reissue']) ? $data['email_reissue'] : null;
        $this->container['expiration_date'] = isset($data['expiration_date']) ? $data['expiration_date'] : null;
        $this->container['features'] = isset($data['features']) ? $data['features'] : null;
        $this->container['host_names'] = isset($data['host_names']) ? $data['host_names'] : null;
        $this->container['id'] = isset($data['id']) ? $data['id'] : null;
        $this->container['intermediate_certificate'] = isset($data['intermediate_certificate']) ? $data['intermediate_certificate'] : null;
        $this->container['options'] = isset($data['options']) ? $data['options'] : null;
        $this->container['order_date'] = isset($data['order_date']) ? $data['order_date'] : null;
        $this->container['organization_handle'] = isset($data['organization_handle']) ? $data['organization_handle'] : null;
        $this->container['period'] = isset($data['period']) ? $data['period'] : null;
        $this->container['product_id'] = isset($data['product_id']) ? $data['product_id'] : null;
        $this->container['product_name'] = isset($data['product_name']) ? $data['product_name'] : null;
        $this->container['root_certificate'] = isset($data['root_certificate']) ? $data['root_certificate'] : null;
        $this->container['software'] = isset($data['software']) ? $data['software'] : null;
        $this->container['sslinhva_order_id'] = isset($data['sslinhva_order_id']) ? $data['sslinhva_order_id'] : null;
        $this->container['status'] = isset($data['status']) ? $data['status'] : null;
        $this->container['technical_handle'] = isset($data['technical_handle']) ? $data['technical_handle'] : null;
        $this->container['validation_method'] = isset($data['validation_method']) ? $data['validation_method'] : null;
        $this->container['vendor_order_id'] = isset($data['vendor_order_id']) ? $data['vendor_order_id'] : null;
        $this->container['vendor_reference_id'] = isset($data['vendor_reference_id']) ? $data['vendor_reference_id'] : null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        return $invalidProperties;
    }

    /**
     * Validate all the properties in the model
     * return true if all passed
     *
     * @return bool True if all properties are valid
     */
    public function valid()
    {
        return count($this->listInvalidProperties()) === 0;
    }


    /**
     * Gets active_date
     *
     * @return string|null
     */
    public function getActiveDate()
    {
        return $this->container['active_date'];
    }

    /**
     * Sets active_date
     *
     * @param string|null $active_date active_date
     *
     * @return $this
     */
    public function setActiveDate($active_date)
    {
        $this->container['active_date'] = $active_date;

        return $this;
    }

    /**
     * Gets additional_data
     *
     * @return \Openprovider\Api\Rest\Client\Ssl\Model\OrderSslOrderAdditionalData[]|null
     */
    public function getAdditionalData()
    {
        return $this->container['additional_data'];
    }

    /**
     * Sets additional_data
     *
     * @param \Openprovider\Api\Rest\Client\Ssl\Model\OrderSslOrderAdditionalData[]|null $additional_data additional_data
     *
     * @return $this
     */
    public function setAdditionalData($additional_data)
    {
        $this->container['additional_data'] = $additional_data;

        return $this;
    }

    /**
     * Gets administrative_handle
     *
     * @return string|null
     */
    public function getAdministrativeHandle()
    {
        return $this->container['administrative_handle'];
    }

    /**
     * Sets administrative_handle
     *
     * @param string|null $administrative_handle administrative_handle
     *
     * @return $this
     */
    public function setAdministrativeHandle($administrative_handle)
    {
        $this->container['administrative_handle'] = $administrative_handle;

        return $this;
    }

    /**
     * Gets autorenew
     *
     * @return string|null
     */
    public function getAutorenew()
    {
        return $this->container['autorenew'];
    }

    /**
     * Sets autorenew
     *
     * @param string|null $autorenew autorenew
     *
     * @return $this
     */
    public function setAutorenew($autorenew)
    {
        $this->container['autorenew'] = $autorenew;

        return $this;
    }

    /**
     * Gets billing_handle
     *
     * @return string|null
     */
    public function getBillingHandle()
    {
        return $this->container['billing_handle'];
    }

    /**
     * Sets billing_handle
     *
     * @param string|null $billing_handle billing_handle
     *
     * @return $this
     */
    public function setBillingHandle($billing_handle)
    {
        $this->container['billing_handle'] = $billing_handle;

        return $this;
    }

    /**
     * Gets brand_name
     *
     * @return string|null
     */
    public function getBrandName()
    {
        return $this->container['brand_name'];
    }

    /**
     * Sets brand_name
     *
     * @param string|null $brand_name brand_name
     *
     * @return $this
     */
    public function setBrandName($brand_name)
    {
        $this->container['brand_name'] = $brand_name;

        return $this;
    }

    /**
     * Gets certificate
     *
     * @return string|null
     */
    public function getCertificate()
    {
        return $this->container['certificate'];
    }

    /**
     * Sets certificate
     *
     * @param string|null $certificate certificate
     *
     * @return $this
     */
    public function setCertificate($certificate)
    {
        $this->container['certificate'] = $certificate;

        return $this;
    }

    /**
     * Gets common_name
     *
     * @return string|null
     */
    public function getCommonName()
    {
        return $this->container['common_name'];
    }

    /**
     * Sets common_name
     *
     * @param string|null $common_name common_name
     *
     * @return $this
     */
    public function setCommonName($common_name)
    {
        $this->container['common_name'] = $common_name;

        return $this;
    }

    /**
     * Gets csr
     *
     * @return string|null
     */
    public function getCsr()
    {
        return $this->container['csr'];
    }

    /**
     * Sets csr
     *
     * @param string|null $csr csr
     *
     * @return $this
     */
    public function setCsr($csr)
    {
        $this->container['csr'] = $csr;

        return $this;
    }

    /**
     * Gets domain_validation_methods
     *
     * @return \Openprovider\Api\Rest\Client\Ssl\Model\OrderSslOrderDomainValidationMethods[]|null
     */
    public function getDomainValidationMethods()
    {
        return $this->container['domain_validation_methods'];
    }

    /**
     * Sets domain_validation_methods
     *
     * @param \Openprovider\Api\Rest\Client\Ssl\Model\OrderSslOrderDomainValidationMethods[]|null $domain_validation_methods domain_validation_methods
     *
     * @return $this
     */
    public function setDomainValidationMethods($domain_validation_methods)
    {
        $this->container['domain_validation_methods'] = $domain_validation_methods;

        return $this;
    }

    /**
     * Gets domain_validation_statuses
     *
     * @return \Openprovider\Api\Rest\Client\Ssl\Model\OrderSslOrderDomainValidationStatuses|null
     */
    public function getDomainValidationStatuses()
    {
        return $this->container['domain_validation_statuses'];
    }

    /**
     * Sets domain_validation_statuses
     *
     * @param \Openprovider\Api\Rest\Client\Ssl\Model\OrderSslOrderDomainValidationStatuses|null $domain_validation_statuses domain_validation_statuses
     *
     * @return $this
     */
    public function setDomainValidationStatuses($domain_validation_statuses)
    {
        $this->container['domain_validation_statuses'] = $domain_validation_statuses;

        return $this;
    }

    /**
     * Gets email_approver
     *
     * @return string|null
     */
    public function getEmailApprover()
    {
        return $this->container['email_approver'];
    }

    /**
     * Sets email_approver
     *
     * @param string|null $email_approver email_approver
     *
     * @return $this
     */
    public function setEmailApprover($email_approver)
    {
        $this->container['email_approver'] = $email_approver;

        return $this;
    }

    /**
     * Gets email_reissue
     *
     * @return string|null
     */
    public function getEmailReissue()
    {
        return $this->container['email_reissue'];
    }

    /**
     * Sets email_reissue
     *
     * @param string|null $email_reissue email_reissue
     *
     * @return $this
     */
    public function setEmailReissue($email_reissue)
    {
        $this->container['email_reissue'] = $email_reissue;

        return $this;
    }

    /**
     * Gets expiration_date
     *
     * @return string|null
     */
    public function getExpirationDate()
    {
        return $this->container['expiration_date'];
    }

    /**
     * Sets expiration_date
     *
     * @param string|null $expiration_date expiration_date
     *
     * @return $this
     */
    public function setExpirationDate($expiration_date)
    {
        $this->container['expiration_date'] = $expiration_date;

        return $this;
    }

    /**
     * Gets features
     *
     * @return string|null
     */
    public function getFeatures()
    {
        return $this->container['features'];
    }

    /**
     * Sets features
     *
     * @param string|null $features features
     *
     * @return $this
     */
    public function setFeatures($features)
    {
        $this->container['features'] = $features;

        return $this;
    }

    /**
     * Gets host_names
     *
     * @return string[]|null
     */
    public function getHostNames()
    {
        return $this->container['host_names'];
    }

    /**
     * Sets host_names
     *
     * @param string[]|null $host_names host_names
     *
     * @return $this
     */
    public function setHostNames($host_names)
    {
        $this->container['host_names'] = $host_names;

        return $this;
    }

    /**
     * Gets id
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->container['id'];
    }

    /**
     * Sets id
     *
     * @param int|null $id id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->container['id'] = $id;

        return $this;
    }

    /**
     * Gets intermediate_certificate
     *
     * @return string|null
     */
    public function getIntermediateCertificate()
    {
        return $this->container['intermediate_certificate'];
    }

    /**
     * Sets intermediate_certificate
     *
     * @param string|null $intermediate_certificate intermediate_certificate
     *
     * @return $this
     */
    public function setIntermediateCertificate($intermediate_certificate)
    {
        $this->container['intermediate_certificate'] = $intermediate_certificate;

        return $this;
    }

    /**
     * Gets options
     *
     * @return \Openprovider\Api\Rest\Client\Ssl\Model\OrderSslOrderOptions|null
     */
    public function getOptions()
    {
        return $this->container['options'];
    }

    /**
     * Sets options
     *
     * @param \Openprovider\Api\Rest\Client\Ssl\Model\OrderSslOrderOptions|null $options options
     *
     * @return $this
     */
    public function setOptions($options)
    {
        $this->container['options'] = $options;

        return $this;
    }

    /**
     * Gets order_date
     *
     * @return string|null
     */
    public function getOrderDate()
    {
        return $this->container['order_date'];
    }

    /**
     * Sets order_date
     *
     * @param string|null $order_date order_date
     *
     * @return $this
     */
    public function setOrderDate($order_date)
    {
        $this->container['order_date'] = $order_date;

        return $this;
    }

    /**
     * Gets organization_handle
     *
     * @return string|null
     */
    public function getOrganizationHandle()
    {
        return $this->container['organization_handle'];
    }

    /**
     * Sets organization_handle
     *
     * @param string|null $organization_handle organization_handle
     *
     * @return $this
     */
    public function setOrganizationHandle($organization_handle)
    {
        $this->container['organization_handle'] = $organization_handle;

        return $this;
    }

    /**
     * Gets period
     *
     * @return int|null
     */
    public function getPeriod()
    {
        return $this->container['period'];
    }

    /**
     * Sets period
     *
     * @param int|null $period period
     *
     * @return $this
     */
    public function setPeriod($period)
    {
        $this->container['period'] = $period;

        return $this;
    }

    /**
     * Gets product_id
     *
     * @return int|null
     */
    public function getProductId()
    {
        return $this->container['product_id'];
    }

    /**
     * Sets product_id
     *
     * @param int|null $product_id product_id
     *
     * @return $this
     */
    public function setProductId($product_id)
    {
        $this->container['product_id'] = $product_id;

        return $this;
    }

    /**
     * Gets product_name
     *
     * @return string|null
     */
    public function getProductName()
    {
        return $this->container['product_name'];
    }

    /**
     * Sets product_name
     *
     * @param string|null $product_name product_name
     *
     * @return $this
     */
    public function setProductName($product_name)
    {
        $this->container['product_name'] = $product_name;

        return $this;
    }

    /**
     * Gets root_certificate
     *
     * @return string|null
     */
    public function getRootCertificate()
    {
        return $this->container['root_certificate'];
    }

    /**
     * Sets root_certificate
     *
     * @param string|null $root_certificate root_certificate
     *
     * @return $this
     */
    public function setRootCertificate($root_certificate)
    {
        $this->container['root_certificate'] = $root_certificate;

        return $this;
    }

    /**
     * Gets software
     *
     * @return string|null
     */
    public function getSoftware()
    {
        return $this->container['software'];
    }

    /**
     * Sets software
     *
     * @param string|null $software software
     *
     * @return $this
     */
    public function setSoftware($software)
    {
        $this->container['software'] = $software;

        return $this;
    }

    /**
     * Gets sslinhva_order_id
     *
     * @return string|null
     */
    public function getSslinhvaOrderId()
    {
        return $this->container['sslinhva_order_id'];
    }

    /**
     * Sets sslinhva_order_id
     *
     * @param string|null $sslinhva_order_id sslinhva_order_id
     *
     * @return $this
     */
    public function setSslinhvaOrderId($sslinhva_order_id)
    {
        $this->container['sslinhva_order_id'] = $sslinhva_order_id;

        return $this;
    }

    /**
     * Gets status
     *
     * @return string|null
     */
    public function getStatus()
    {
        return $this->container['status'];
    }

    /**
     * Sets status
     *
     * @param string|null $status status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->container['status'] = $status;

        return $this;
    }

    /**
     * Gets technical_handle
     *
     * @return string|null
     */
    public function getTechnicalHandle()
    {
        return $this->container['technical_handle'];
    }

    /**
     * Sets technical_handle
     *
     * @param string|null $technical_handle technical_handle
     *
     * @return $this
     */
    public function setTechnicalHandle($technical_handle)
    {
        $this->container['technical_handle'] = $technical_handle;

        return $this;
    }

    /**
     * Gets validation_method
     *
     * @return string|null
     */
    public function getValidationMethod()
    {
        return $this->container['validation_method'];
    }

    /**
     * Sets validation_method
     *
     * @param string|null $validation_method validation_method
     *
     * @return $this
     */
    public function setValidationMethod($validation_method)
    {
        $this->container['validation_method'] = $validation_method;

        return $this;
    }

    /**
     * Gets vendor_order_id
     *
     * @return string|null
     */
    public function getVendorOrderId()
    {
        return $this->container['vendor_order_id'];
    }

    /**
     * Sets vendor_order_id
     *
     * @param string|null $vendor_order_id vendor_order_id
     *
     * @return $this
     */
    public function setVendorOrderId($vendor_order_id)
    {
        $this->container['vendor_order_id'] = $vendor_order_id;

        return $this;
    }

    /**
     * Gets vendor_reference_id
     *
     * @return string|null
     */
    public function getVendorReferenceId()
    {
        return $this->container['vendor_reference_id'];
    }

    /**
     * Sets vendor_reference_id
     *
     * @param string|null $vendor_reference_id vendor_reference_id
     *
     * @return $this
     */
    public function setVendorReferenceId($vendor_reference_id)
    {
        $this->container['vendor_reference_id'] = $vendor_reference_id;

        return $this;
    }
    /**
     * Returns true if offset exists. False otherwise.
     *
     * @param integer $offset Offset
     *
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     *
     * @param integer $offset Offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    /**
     * Sets value based on offset.
     *
     * @param integer $offset Offset
     * @param mixed   $value  Value to be set
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Unsets offset.
     *
     * @param integer $offset Offset
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    /**
     * Gets the string presentation of the object
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode(
            ObjectSerializer::sanitizeForSerialization($this),
            JSON_PRETTY_PRINT
        );
    }
}

