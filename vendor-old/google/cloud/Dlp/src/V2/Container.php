<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/privacy/dlp/v2/dlp.proto

namespace Google\Cloud\Dlp\V2;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Represents a container that may contain DLP findings.
 * Examples of a container include a file, table, or database record.
 *
 * Generated from protobuf message <code>google.privacy.dlp.v2.Container</code>
 */
class Container extends \Google\Protobuf\Internal\Message
{
    /**
     * Container type, for example BigQuery or Google Cloud Storage.
     *
     * Generated from protobuf field <code>string type = 1;</code>
     */
    private $type = '';
    /**
     * Project where the finding was found.
     * Can be different from the project that owns the finding.
     *
     * Generated from protobuf field <code>string project_id = 2;</code>
     */
    private $project_id = '';
    /**
     * A string representation of the full container name.
     * Examples:
     * - BigQuery: 'Project:DataSetId.TableId'
     * - Google Cloud Storage: 'gs://Bucket/folders/filename.txt'
     *
     * Generated from protobuf field <code>string full_path = 3;</code>
     */
    private $full_path = '';
    /**
     * The root of the container.
     * Examples:
     * - For BigQuery table `project_id:dataset_id.table_id`, the root is
     *  `dataset_id`
     * - For Google Cloud Storage file `gs://bucket/folder/filename.txt`, the root
     *  is `gs://bucket`
     *
     * Generated from protobuf field <code>string root_path = 4;</code>
     */
    private $root_path = '';
    /**
     * The rest of the path after the root.
     * Examples:
     * - For BigQuery table `project_id:dataset_id.table_id`, the relative path is
     *  `table_id`
     * - Google Cloud Storage file `gs://bucket/folder/filename.txt`, the relative
     *  path is `folder/filename.txt`
     *
     * Generated from protobuf field <code>string relative_path = 5;</code>
     */
    private $relative_path = '';
    /**
     * Findings container modification timestamp, if applicable.
     * For Google Cloud Storage contains last file modification timestamp.
     * For BigQuery table contains last_modified_time property.
     * For Datastore - not populated.
     *
     * Generated from protobuf field <code>.google.protobuf.Timestamp update_time = 6;</code>
     */
    private $update_time = null;
    /**
     * Findings container version, if available
     * ("generation" for Google Cloud Storage).
     *
     * Generated from protobuf field <code>string version = 7;</code>
     */
    private $version = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $type
     *           Container type, for example BigQuery or Google Cloud Storage.
     *     @type string $project_id
     *           Project where the finding was found.
     *           Can be different from the project that owns the finding.
     *     @type string $full_path
     *           A string representation of the full container name.
     *           Examples:
     *           - BigQuery: 'Project:DataSetId.TableId'
     *           - Google Cloud Storage: 'gs://Bucket/folders/filename.txt'
     *     @type string $root_path
     *           The root of the container.
     *           Examples:
     *           - For BigQuery table `project_id:dataset_id.table_id`, the root is
     *            `dataset_id`
     *           - For Google Cloud Storage file `gs://bucket/folder/filename.txt`, the root
     *            is `gs://bucket`
     *     @type string $relative_path
     *           The rest of the path after the root.
     *           Examples:
     *           - For BigQuery table `project_id:dataset_id.table_id`, the relative path is
     *            `table_id`
     *           - Google Cloud Storage file `gs://bucket/folder/filename.txt`, the relative
     *            path is `folder/filename.txt`
     *     @type \Google\Protobuf\Timestamp $update_time
     *           Findings container modification timestamp, if applicable.
     *           For Google Cloud Storage contains last file modification timestamp.
     *           For BigQuery table contains last_modified_time property.
     *           For Datastore - not populated.
     *     @type string $version
     *           Findings container version, if available
     *           ("generation" for Google Cloud Storage).
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Privacy\Dlp\V2\Dlp::initOnce();
        parent::__construct($data);
    }

    /**
     * Container type, for example BigQuery or Google Cloud Storage.
     *
     * Generated from protobuf field <code>string type = 1;</code>
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Container type, for example BigQuery or Google Cloud Storage.
     *
     * Generated from protobuf field <code>string type = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setType($var)
    {
        GPBUtil::checkString($var, True);
        $this->type = $var;

        return $this;
    }

    /**
     * Project where the finding was found.
     * Can be different from the project that owns the finding.
     *
     * Generated from protobuf field <code>string project_id = 2;</code>
     * @return string
     */
    public function getProjectId()
    {
        return $this->project_id;
    }

    /**
     * Project where the finding was found.
     * Can be different from the project that owns the finding.
     *
     * Generated from protobuf field <code>string project_id = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setProjectId($var)
    {
        GPBUtil::checkString($var, True);
        $this->project_id = $var;

        return $this;
    }

    /**
     * A string representation of the full container name.
     * Examples:
     * - BigQuery: 'Project:DataSetId.TableId'
     * - Google Cloud Storage: 'gs://Bucket/folders/filename.txt'
     *
     * Generated from protobuf field <code>string full_path = 3;</code>
     * @return string
     */
    public function getFullPath()
    {
        return $this->full_path;
    }

    /**
     * A string representation of the full container name.
     * Examples:
     * - BigQuery: 'Project:DataSetId.TableId'
     * - Google Cloud Storage: 'gs://Bucket/folders/filename.txt'
     *
     * Generated from protobuf field <code>string full_path = 3;</code>
     * @param string $var
     * @return $this
     */
    public function setFullPath($var)
    {
        GPBUtil::checkString($var, True);
        $this->full_path = $var;

        return $this;
    }

    /**
     * The root of the container.
     * Examples:
     * - For BigQuery table `project_id:dataset_id.table_id`, the root is
     *  `dataset_id`
     * - For Google Cloud Storage file `gs://bucket/folder/filename.txt`, the root
     *  is `gs://bucket`
     *
     * Generated from protobuf field <code>string root_path = 4;</code>
     * @return string
     */
    public function getRootPath()
    {
        return $this->root_path;
    }

    /**
     * The root of the container.
     * Examples:
     * - For BigQuery table `project_id:dataset_id.table_id`, the root is
     *  `dataset_id`
     * - For Google Cloud Storage file `gs://bucket/folder/filename.txt`, the root
     *  is `gs://bucket`
     *
     * Generated from protobuf field <code>string root_path = 4;</code>
     * @param string $var
     * @return $this
     */
    public function setRootPath($var)
    {
        GPBUtil::checkString($var, True);
        $this->root_path = $var;

        return $this;
    }

    /**
     * The rest of the path after the root.
     * Examples:
     * - For BigQuery table `project_id:dataset_id.table_id`, the relative path is
     *  `table_id`
     * - Google Cloud Storage file `gs://bucket/folder/filename.txt`, the relative
     *  path is `folder/filename.txt`
     *
     * Generated from protobuf field <code>string relative_path = 5;</code>
     * @return string
     */
    public function getRelativePath()
    {
        return $this->relative_path;
    }

    /**
     * The rest of the path after the root.
     * Examples:
     * - For BigQuery table `project_id:dataset_id.table_id`, the relative path is
     *  `table_id`
     * - Google Cloud Storage file `gs://bucket/folder/filename.txt`, the relative
     *  path is `folder/filename.txt`
     *
     * Generated from protobuf field <code>string relative_path = 5;</code>
     * @param string $var
     * @return $this
     */
    public function setRelativePath($var)
    {
        GPBUtil::checkString($var, True);
        $this->relative_path = $var;

        return $this;
    }

    /**
     * Findings container modification timestamp, if applicable.
     * For Google Cloud Storage contains last file modification timestamp.
     * For BigQuery table contains last_modified_time property.
     * For Datastore - not populated.
     *
     * Generated from protobuf field <code>.google.protobuf.Timestamp update_time = 6;</code>
     * @return \Google\Protobuf\Timestamp
     */
    public function getUpdateTime()
    {
        return isset($this->update_time) ? $this->update_time : null;
    }

    public function hasUpdateTime()
    {
        return isset($this->update_time);
    }

    public function clearUpdateTime()
    {
        unset($this->update_time);
    }

    /**
     * Findings container modification timestamp, if applicable.
     * For Google Cloud Storage contains last file modification timestamp.
     * For BigQuery table contains last_modified_time property.
     * For Datastore - not populated.
     *
     * Generated from protobuf field <code>.google.protobuf.Timestamp update_time = 6;</code>
     * @param \Google\Protobuf\Timestamp $var
     * @return $this
     */
    public function setUpdateTime($var)
    {
        GPBUtil::checkMessage($var, \Google\Protobuf\Timestamp::class);
        $this->update_time = $var;

        return $this;
    }

    /**
     * Findings container version, if available
     * ("generation" for Google Cloud Storage).
     *
     * Generated from protobuf field <code>string version = 7;</code>
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Findings container version, if available
     * ("generation" for Google Cloud Storage).
     *
     * Generated from protobuf field <code>string version = 7;</code>
     * @param string $var
     * @return $this
     */
    public function setVersion($var)
    {
        GPBUtil::checkString($var, True);
        $this->version = $var;

        return $this;
    }

}

