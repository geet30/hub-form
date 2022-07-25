<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/firestore/v1/common.proto

namespace Google\Cloud\Firestore\V1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Options for creating a new transaction.
 *
 * Generated from protobuf message <code>google.firestore.v1.TransactionOptions</code>
 */
class TransactionOptions extends \Google\Protobuf\Internal\Message
{
    protected $mode;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \Google\Cloud\Firestore\V1\TransactionOptions\ReadOnly $read_only
     *           The transaction can only be used for read operations.
     *     @type \Google\Cloud\Firestore\V1\TransactionOptions\ReadWrite $read_write
     *           The transaction can be used for both read and write operations.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Firestore\V1\Common::initOnce();
        parent::__construct($data);
    }

    /**
     * The transaction can only be used for read operations.
     *
     * Generated from protobuf field <code>.google.firestore.v1.TransactionOptions.ReadOnly read_only = 2;</code>
     * @return \Google\Cloud\Firestore\V1\TransactionOptions\ReadOnly
     */
    public function getReadOnly()
    {
        return $this->readOneof(2);
    }

    public function hasReadOnly()
    {
        return $this->hasOneof(2);
    }

    /**
     * The transaction can only be used for read operations.
     *
     * Generated from protobuf field <code>.google.firestore.v1.TransactionOptions.ReadOnly read_only = 2;</code>
     * @param \Google\Cloud\Firestore\V1\TransactionOptions\ReadOnly $var
     * @return $this
     */
    public function setReadOnly($var)
    {
        GPBUtil::checkMessage($var, \Google\Cloud\Firestore\V1\TransactionOptions\ReadOnly::class);
        $this->writeOneof(2, $var);

        return $this;
    }

    /**
     * The transaction can be used for both read and write operations.
     *
     * Generated from protobuf field <code>.google.firestore.v1.TransactionOptions.ReadWrite read_write = 3;</code>
     * @return \Google\Cloud\Firestore\V1\TransactionOptions\ReadWrite
     */
    public function getReadWrite()
    {
        return $this->readOneof(3);
    }

    public function hasReadWrite()
    {
        return $this->hasOneof(3);
    }

    /**
     * The transaction can be used for both read and write operations.
     *
     * Generated from protobuf field <code>.google.firestore.v1.TransactionOptions.ReadWrite read_write = 3;</code>
     * @param \Google\Cloud\Firestore\V1\TransactionOptions\ReadWrite $var
     * @return $this
     */
    public function setReadWrite($var)
    {
        GPBUtil::checkMessage($var, \Google\Cloud\Firestore\V1\TransactionOptions\ReadWrite::class);
        $this->writeOneof(3, $var);

        return $this;
    }

    /**
     * @return string
     */
    public function getMode()
    {
        return $this->whichOneof("mode");
    }

}

