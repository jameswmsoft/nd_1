<?php
/**
 * The contents of this file was generated using the WSDLs as provided by eBay.
 *
 * DO NOT EDIT THIS FILE!
 */

namespace DTS\eBaySDK\MerchantData\Types;

/**
 *
 * @property boolean $ShipToRegistrationCountry
 * @property boolean $ZeroFeedbackScore
 * @property integer $MinimumFeedbackScore
 * @property \DTS\eBaySDK\MerchantData\Types\MaximumItemRequirementsType $MaximumItemRequirements
 * @property boolean $LinkedPayPalAccount
 * @property \DTS\eBaySDK\MerchantData\Types\VerifiedUserRequirementsType $VerifiedUserRequirements
 * @property \DTS\eBaySDK\MerchantData\Types\MaximumUnpaidItemStrikesInfoType $MaximumUnpaidItemStrikesInfo
 * @property \DTS\eBaySDK\MerchantData\Types\MaximumBuyerPolicyViolationsType $MaximumBuyerPolicyViolations
 */
class BuyerRequirementDetailsType extends \DTS\eBaySDK\Types\BaseType
{
    /**
     * @var array Properties belonging to objects of this class.
     */
    private static $propertyTypes = [
        'ShipToRegistrationCountry' => [
            'type' => 'boolean',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'ShipToRegistrationCountry'
        ],
        'ZeroFeedbackScore' => [
            'type' => 'boolean',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'ZeroFeedbackScore'
        ],
        'MinimumFeedbackScore' => [
            'type' => 'integer',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'MinimumFeedbackScore'
        ],
        'MaximumItemRequirements' => [
            'type' => 'DTS\eBaySDK\MerchantData\Types\MaximumItemRequirementsType',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'MaximumItemRequirements'
        ],
        'LinkedPayPalAccount' => [
            'type' => 'boolean',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'LinkedPayPalAccount'
        ],
        'VerifiedUserRequirements' => [
            'type' => 'DTS\eBaySDK\MerchantData\Types\VerifiedUserRequirementsType',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'VerifiedUserRequirements'
        ],
        'MaximumUnpaidItemStrikesInfo' => [
            'type' => 'DTS\eBaySDK\MerchantData\Types\MaximumUnpaidItemStrikesInfoType',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'MaximumUnpaidItemStrikesInfo'
        ],
        'MaximumBuyerPolicyViolations' => [
            'type' => 'DTS\eBaySDK\MerchantData\Types\MaximumBuyerPolicyViolationsType',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'MaximumBuyerPolicyViolations'
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
