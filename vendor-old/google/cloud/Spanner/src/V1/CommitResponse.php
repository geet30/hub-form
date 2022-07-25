<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/spanner/v1/spanner.proto

namespace Google\Cloud\Spanner\V1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * The response for [Commit][google.spanner.v1.Spanner.Commit].
 *
 * Generated from protobuf message <code>google.spanner.v1.CommitResponse</code>
 */
class CommitResponse extends \Google\Protobuf\Internal\Message
{
    /**
     * The Cloud Spanner timestamp at which the transaction committed.
     *
     * Generated from protobuf field <code>.google.protobuf.Timestamp commit_timestamp = 1;</code>
     */
    private $commit_timestamp = null;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \Google\Protobuf\Timestamp $commit_timestamp
     *           The Cloud Spanner timestamp at which the transaction committed.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Spanner\V1\Spanner::initOnce();
        parent::__construct($data);
    }

    /**
     * The Cloud Spanner timestamp at which the transaction committed.
     *
     * Generated from protobuf field <code>.google.protobuf.Timestamp commit_timestamp = 1;</code>
     * @return \Google\Protobuf\Timestamp
     */
    public function getCommitTimestamp()
    {
        return isset($this->commit_timestamp) ? $this->commit_timestamp : null;
    }

    public function hasCommitTimestamp()
    {
        return isset($this->commit_timestamp);
    }

    public function clearCommitTimestamp()
    {
        unset($this->commit_timestamp);
    }

    /**
     * The Cloud Spanner timestamp at which the transaction committed.
     *
     * Generated from protobuf field <code>.google.protobuf.Timestamp commit_timestamp = 1;</code>
     * @param \Google\Protobuf\Timestamp $var
     * @return $this
     */
    public function setCommitTimestamp($var)
    {
        GPBUtil::checkMessage($var, \Google\Protobuf\Timestamp::class);
        $this->commit_timestamp = $var;

        return $this;
    }

}
