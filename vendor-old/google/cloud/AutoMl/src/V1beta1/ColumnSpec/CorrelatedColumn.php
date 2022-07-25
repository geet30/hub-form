<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/automl/v1beta1/column_spec.proto

namespace Google\Cloud\AutoMl\V1beta1\ColumnSpec;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Identifies the table's column, and its correlation with the column this
 * ColumnSpec describes.
 *
 * Generated from protobuf message <code>google.cloud.automl.v1beta1.ColumnSpec.CorrelatedColumn</code>
 */
class CorrelatedColumn extends \Google\Protobuf\Internal\Message
{
    /**
     * The column_spec_id of the correlated column, which belongs to the same
     * table as the in-context column.
     *
     * Generated from protobuf field <code>string column_spec_id = 1;</code>
     */
    private $column_spec_id = '';
    /**
     * Correlation between this and the in-context column.
     *
     * Generated from protobuf field <code>.google.cloud.automl.v1beta1.CorrelationStats correlation_stats = 2;</code>
     */
    private $correlation_stats = null;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $column_spec_id
     *           The column_spec_id of the correlated column, which belongs to the same
     *           table as the in-context column.
     *     @type \Google\Cloud\AutoMl\V1beta1\CorrelationStats $correlation_stats
     *           Correlation between this and the in-context column.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Cloud\Automl\V1Beta1\ColumnSpec::initOnce();
        parent::__construct($data);
    }

    /**
     * The column_spec_id of the correlated column, which belongs to the same
     * table as the in-context column.
     *
     * Generated from protobuf field <code>string column_spec_id = 1;</code>
     * @return string
     */
    public function getColumnSpecId()
    {
        return $this->column_spec_id;
    }

    /**
     * The column_spec_id of the correlated column, which belongs to the same
     * table as the in-context column.
     *
     * Generated from protobuf field <code>string column_spec_id = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setColumnSpecId($var)
    {
        GPBUtil::checkString($var, True);
        $this->column_spec_id = $var;

        return $this;
    }

    /**
     * Correlation between this and the in-context column.
     *
     * Generated from protobuf field <code>.google.cloud.automl.v1beta1.CorrelationStats correlation_stats = 2;</code>
     * @return \Google\Cloud\AutoMl\V1beta1\CorrelationStats
     */
    public function getCorrelationStats()
    {
        return isset($this->correlation_stats) ? $this->correlation_stats : null;
    }

    public function hasCorrelationStats()
    {
        return isset($this->correlation_stats);
    }

    public function clearCorrelationStats()
    {
        unset($this->correlation_stats);
    }

    /**
     * Correlation between this and the in-context column.
     *
     * Generated from protobuf field <code>.google.cloud.automl.v1beta1.CorrelationStats correlation_stats = 2;</code>
     * @param \Google\Cloud\AutoMl\V1beta1\CorrelationStats $var
     * @return $this
     */
    public function setCorrelationStats($var)
    {
        GPBUtil::checkMessage($var, \Google\Cloud\AutoMl\V1beta1\CorrelationStats::class);
        $this->correlation_stats = $var;

        return $this;
    }

}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(CorrelatedColumn::class, \Google\Cloud\AutoMl\V1beta1\ColumnSpec_CorrelatedColumn::class);

