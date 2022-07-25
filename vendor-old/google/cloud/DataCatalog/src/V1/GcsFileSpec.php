<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/datacatalog/v1/gcs_fileset_spec.proto

namespace Google\Cloud\DataCatalog\V1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Specifications of a single file in Cloud Storage.
 *
 * Generated from protobuf message <code>google.cloud.datacatalog.v1.GcsFileSpec</code>
 */
class GcsFileSpec extends \Google\Protobuf\Internal\Message
{
    /**
     * Required. The full file path. Example: `gs://bucket_name/a/b.txt`.
     *
     * Generated from protobuf field <code>string file_path = 1 [(.google.api.field_behavior) = REQUIRED];</code>
     */
    private $file_path = '';
    /**
     * Output only. Timestamps about the Cloud Storage file.
     *
     * Generated from protobuf field <code>.google.cloud.datacatalog.v1.SystemTimestamps gcs_timestamps = 2 [(.google.api.field_behavior) = OUTPUT_ONLY];</code>
     */
    private $gcs_timestamps = null;
    /**
     * Output only. The size of the file, in bytes.
     *
     * Generated from protobuf field <code>int64 size_bytes = 4 [(.google.api.field_behavior) = OUTPUT_ONLY];</code>
     */
    private $size_bytes = 0;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $file_path
     *           Required. The full file path. Example: `gs://bucket_name/a/b.txt`.
     *     @type \Google\Cloud\DataCatalog\V1\SystemTimestamps $gcs_timestamps
     *           Output only. Timestamps about the Cloud Storage file.
     *     @type int|string $size_bytes
     *           Output only. The size of the file, in bytes.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Cloud\Datacatalog\V1\GcsFilesetSpec::initOnce();
        parent::__construct($data);
    }

    /**
     * Required. The full file path. Example: `gs://bucket_name/a/b.txt`.
     *
     * Generated from protobuf field <code>string file_path = 1 [(.google.api.field_behavior) = REQUIRED];</code>
     * @return string
     */
    public function getFilePath()
    {
        return $this->file_path;
    }

    /**
     * Required. The full file path. Example: `gs://bucket_name/a/b.txt`.
     *
     * Generated from protobuf field <code>string file_path = 1 [(.google.api.field_behavior) = REQUIRED];</code>
     * @param string $var
     * @return $this
     */
    public function setFilePath($var)
    {
        GPBUtil::checkString($var, True);
        $this->file_path = $var;

        return $this;
    }

    /**
     * Output only. Timestamps about the Cloud Storage file.
     *
     * Generated from protobuf field <code>.google.cloud.datacatalog.v1.SystemTimestamps gcs_timestamps = 2 [(.google.api.field_behavior) = OUTPUT_ONLY];</code>
     * @return \Google\Cloud\DataCatalog\V1\SystemTimestamps
     */
    public function getGcsTimestamps()
    {
        return isset($this->gcs_timestamps) ? $this->gcs_timestamps : null;
    }

    public function hasGcsTimestamps()
    {
        return isset($this->gcs_timestamps);
    }

    public function clearGcsTimestamps()
    {
        unset($this->gcs_timestamps);
    }

    /**
     * Output only. Timestamps about the Cloud Storage file.
     *
     * Generated from protobuf field <code>.google.cloud.datacatalog.v1.SystemTimestamps gcs_timestamps = 2 [(.google.api.field_behavior) = OUTPUT_ONLY];</code>
     * @param \Google\Cloud\DataCatalog\V1\SystemTimestamps $var
     * @return $this
     */
    public function setGcsTimestamps($var)
    {
        GPBUtil::checkMessage($var, \Google\Cloud\DataCatalog\V1\SystemTimestamps::class);
        $this->gcs_timestamps = $var;

        return $this;
    }

    /**
     * Output only. The size of the file, in bytes.
     *
     * Generated from protobuf field <code>int64 size_bytes = 4 [(.google.api.field_behavior) = OUTPUT_ONLY];</code>
     * @return int|string
     */
    public function getSizeBytes()
    {
        return $this->size_bytes;
    }

    /**
     * Output only. The size of the file, in bytes.
     *
     * Generated from protobuf field <code>int64 size_bytes = 4 [(.google.api.field_behavior) = OUTPUT_ONLY];</code>
     * @param int|string $var
     * @return $this
     */
    public function setSizeBytes($var)
    {
        GPBUtil::checkInt64($var);
        $this->size_bytes = $var;

        return $this;
    }

}
