<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/privacy/dlp/v2/storage.proto

namespace Google\Cloud\Dlp\V2;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Type of information detected by the API.
 *
 * Generated from protobuf message <code>google.privacy.dlp.v2.InfoType</code>
 */
class InfoType extends \Google\Protobuf\Internal\Message
{
    /**
     * Name of the information type. Either a name of your choosing when
     * creating a CustomInfoType, or one of the names listed
     * at https://cloud.google.com/dlp/docs/infotypes-reference when specifying
     * a built-in type.  When sending Cloud DLP results to Data Catalog, infoType
     * names should conform to the pattern `[A-Za-z0-9$-_]{1,64}`.
     *
     * Generated from protobuf field <code>string name = 1;</code>
     */
    private $name = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $name
     *           Name of the information type. Either a name of your choosing when
     *           creating a CustomInfoType, or one of the names listed
     *           at https://cloud.google.com/dlp/docs/infotypes-reference when specifying
     *           a built-in type.  When sending Cloud DLP results to Data Catalog, infoType
     *           names should conform to the pattern `[A-Za-z0-9$-_]{1,64}`.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Privacy\Dlp\V2\Storage::initOnce();
        parent::__construct($data);
    }

    /**
     * Name of the information type. Either a name of your choosing when
     * creating a CustomInfoType, or one of the names listed
     * at https://cloud.google.com/dlp/docs/infotypes-reference when specifying
     * a built-in type.  When sending Cloud DLP results to Data Catalog, infoType
     * names should conform to the pattern `[A-Za-z0-9$-_]{1,64}`.
     *
     * Generated from protobuf field <code>string name = 1;</code>
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Name of the information type. Either a name of your choosing when
     * creating a CustomInfoType, or one of the names listed
     * at https://cloud.google.com/dlp/docs/infotypes-reference when specifying
     * a built-in type.  When sending Cloud DLP results to Data Catalog, infoType
     * names should conform to the pattern `[A-Za-z0-9$-_]{1,64}`.
     *
     * Generated from protobuf field <code>string name = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setName($var)
    {
        GPBUtil::checkString($var, True);
        $this->name = $var;

        return $this;
    }

}

