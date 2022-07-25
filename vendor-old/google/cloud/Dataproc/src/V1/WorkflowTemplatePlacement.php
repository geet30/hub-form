<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/dataproc/v1/workflow_templates.proto

namespace Google\Cloud\Dataproc\V1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Specifies workflow execution target.
 * Either `managed_cluster` or `cluster_selector` is required.
 *
 * Generated from protobuf message <code>google.cloud.dataproc.v1.WorkflowTemplatePlacement</code>
 */
class WorkflowTemplatePlacement extends \Google\Protobuf\Internal\Message
{
    protected $placement;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \Google\Cloud\Dataproc\V1\ManagedCluster $managed_cluster
     *           A cluster that is managed by the workflow.
     *     @type \Google\Cloud\Dataproc\V1\ClusterSelector $cluster_selector
     *           Optional. A selector that chooses target cluster for jobs based
     *           on metadata.
     *           The selector is evaluated at the time each job is submitted.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Cloud\Dataproc\V1\WorkflowTemplates::initOnce();
        parent::__construct($data);
    }

    /**
     * A cluster that is managed by the workflow.
     *
     * Generated from protobuf field <code>.google.cloud.dataproc.v1.ManagedCluster managed_cluster = 1;</code>
     * @return \Google\Cloud\Dataproc\V1\ManagedCluster
     */
    public function getManagedCluster()
    {
        return $this->readOneof(1);
    }

    public function hasManagedCluster()
    {
        return $this->hasOneof(1);
    }

    /**
     * A cluster that is managed by the workflow.
     *
     * Generated from protobuf field <code>.google.cloud.dataproc.v1.ManagedCluster managed_cluster = 1;</code>
     * @param \Google\Cloud\Dataproc\V1\ManagedCluster $var
     * @return $this
     */
    public function setManagedCluster($var)
    {
        GPBUtil::checkMessage($var, \Google\Cloud\Dataproc\V1\ManagedCluster::class);
        $this->writeOneof(1, $var);

        return $this;
    }

    /**
     * Optional. A selector that chooses target cluster for jobs based
     * on metadata.
     * The selector is evaluated at the time each job is submitted.
     *
     * Generated from protobuf field <code>.google.cloud.dataproc.v1.ClusterSelector cluster_selector = 2;</code>
     * @return \Google\Cloud\Dataproc\V1\ClusterSelector
     */
    public function getClusterSelector()
    {
        return $this->readOneof(2);
    }

    public function hasClusterSelector()
    {
        return $this->hasOneof(2);
    }

    /**
     * Optional. A selector that chooses target cluster for jobs based
     * on metadata.
     * The selector is evaluated at the time each job is submitted.
     *
     * Generated from protobuf field <code>.google.cloud.dataproc.v1.ClusterSelector cluster_selector = 2;</code>
     * @param \Google\Cloud\Dataproc\V1\ClusterSelector $var
     * @return $this
     */
    public function setClusterSelector($var)
    {
        GPBUtil::checkMessage($var, \Google\Cloud\Dataproc\V1\ClusterSelector::class);
        $this->writeOneof(2, $var);

        return $this;
    }

    /**
     * @return string
     */
    public function getPlacement()
    {
        return $this->whichOneof("placement");
    }

}

