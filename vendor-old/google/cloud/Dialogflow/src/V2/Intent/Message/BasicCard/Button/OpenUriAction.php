<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/dialogflow/v2/intent.proto

namespace Google\Cloud\Dialogflow\V2\Intent\Message\BasicCard\Button;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Opens the given URI.
 *
 * Generated from protobuf message <code>google.cloud.dialogflow.v2.Intent.Message.BasicCard.Button.OpenUriAction</code>
 */
class OpenUriAction extends \Google\Protobuf\Internal\Message
{
    /**
     * Required. The HTTP or HTTPS scheme URI.
     *
     * Generated from protobuf field <code>string uri = 1;</code>
     */
    private $uri = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $uri
     *           Required. The HTTP or HTTPS scheme URI.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Cloud\Dialogflow\V2\Intent::initOnce();
        parent::__construct($data);
    }

    /**
     * Required. The HTTP or HTTPS scheme URI.
     *
     * Generated from protobuf field <code>string uri = 1;</code>
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Required. The HTTP or HTTPS scheme URI.
     *
     * Generated from protobuf field <code>string uri = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setUri($var)
    {
        GPBUtil::checkString($var, True);
        $this->uri = $var;

        return $this;
    }

}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(OpenUriAction::class, \Google\Cloud\Dialogflow\V2\Intent_Message_BasicCard_Button_OpenUriAction::class);

