<?php
/**
 * The contents of this file was generated using the WSDLs as provided by eBay.
 *
 * DO NOT EDIT THIS FILE!
 */

namespace DTS\eBaySDK\FileTransfer\Types;

/**
 *
 * @property string $taskReferenceId
 * @property string $fileReferenceId
 * @property string $fileFormat
 * @property \DTS\eBaySDK\FileTransfer\Types\FileAttachment $fileAttachment
 */
class UploadFileRequest extends \DTS\eBaySDK\FileTransfer\Types\BaseServiceRequest
{
    /**
     * @var array Properties belonging to objects of this class.
     */
    private static $propertyTypes = [
        'taskReferenceId' => [
            'type' => 'string',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'taskReferenceId'
        ],
        'fileReferenceId' => [
            'type' => 'string',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'fileReferenceId'
        ],
        'fileFormat' => [
            'type' => 'string',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'fileFormat'
        ],
        'fileAttachment' => [
            'type' => 'DTS\eBaySDK\FileTransfer\Types\FileAttachment',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'fileAttachment'
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
            self::$xmlNamespaces[__CLASS__] = 'xmlns="http://www.ebay.com/marketplace/services"';
        }

        if (!array_key_exists(__CLASS__, self::$requestXmlRootElementNames)) {
            self::$requestXmlRootElementNames[__CLASS__] = 'uploadFileRequest';
        }

        $this->setValues(__CLASS__, $childValues);
    }
}
