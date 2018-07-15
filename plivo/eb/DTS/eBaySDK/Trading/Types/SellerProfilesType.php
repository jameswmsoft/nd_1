<?php
/**
 * The contents of this file was generated using the WSDLs as provided by eBay.
 *
 * DO NOT EDIT THIS FILE!
 */

namespace DTS\eBaySDK\Trading\Types;

/**
 *
 * @property \DTS\eBaySDK\Trading\Types\SellerShippingProfileType $SellerShippingProfile
 * @property \DTS\eBaySDK\Trading\Types\SellerReturnProfileType $SellerReturnProfile
 * @property \DTS\eBaySDK\Trading\Types\SellerPaymentProfileType $SellerPaymentProfile
 */
class SellerProfilesType extends \DTS\eBaySDK\Types\BaseType
{
    /**
     * @var array Properties belonging to objects of this class.
     */
    private static $propertyTypes = [
        'SellerShippingProfile' => [
            'type' => 'DTS\eBaySDK\Trading\Types\SellerShippingProfileType',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'SellerShippingProfile'
        ],
        'SellerReturnProfile' => [
            'type' => 'DTS\eBaySDK\Trading\Types\SellerReturnProfileType',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'SellerReturnProfile'
        ],
        'SellerPaymentProfile' => [
            'type' => 'DTS\eBaySDK\Trading\Types\SellerPaymentProfileType',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'SellerPaymentProfile'
        ]
    ];

    /**
     * @param array $values Optional properties and values to assign to the object.
     */
    public function __construct(array $values = [])
    {
        list($parentValues, $childValues) = self::getParentValues(self::$propertyTypes, $values);

        parent::__construct($parentValues);

        if (!array_key_exists(__CLASS__, self::$properties)) {
            self::$properties[__CLASS__] = array_merge(self::$properties[get_parent_class()], self::$propertyTypes);
        }

        if (!array_key_exists(__CLASS__, self::$xmlNamespaces)) {
            self::$xmlNamespaces[__CLASS__] = 'xmlns="urn:ebay:apis:eBLBaseComponents"';
        }

        $this->setValues(__CLASS__, $childValues);
    }
}
