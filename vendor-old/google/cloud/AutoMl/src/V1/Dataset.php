<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/automl/v1/dataset.proto

namespace Google\Cloud\AutoMl\V1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * A workspace for solving a single, particular machine learning (ML) problem.
 * A workspace contains examples that may be annotated.
 *
 * Generated from protobuf message <code>google.cloud.automl.v1.Dataset</code>
 */
class Dataset extends \Google\Protobuf\Internal\Message
{
    /**
     * Output only. The resource name of the dataset.
     * Form: `projects/{project_id}/locations/{location_id}/datasets/{dataset_id}`
     *
     * Generated from protobuf field <code>string name = 1;</code>
     */
    private $name = '';
    /**
     * Required. The name of the dataset to show in the interface. The name can be
     * up to 32 characters long and can consist only of ASCII Latin letters A-Z
     * and a-z, underscores
     * (_), and ASCII digits 0-9.
     *
     * Generated from protobuf field <code>string display_name = 2;</code>
     */
    private $display_name = '';
    /**
     * User-provided description of the dataset. The description can be up to
     * 25000 characters long.
     *
     * Generated from protobuf field <code>string description = 3;</code>
     */
    private $description = '';
    /**
     * Output only. The number of examples in the dataset.
     *
     * Generated from protobuf field <code>int32 example_count = 21;</code>
     */
    private $example_count = 0;
    /**
     * Output only. Timestamp when this dataset was created.
     *
     * Generated from protobuf field <code>.google.protobuf.Timestamp create_time = 14;</code>
     */
    private $create_time = null;
    /**
     * Used to perform consistent read-modify-write updates. If not set, a blind
     * "overwrite" update happens.
     *
     * Generated from protobuf field <code>string etag = 17;</code>
     */
    private $etag = '';
    /**
     * Optional. The labels with user-defined metadata to organize your dataset.
     * Label keys and values can be no longer than 64 characters
     * (Unicode codepoints), can only contain lowercase letters, numeric
     * characters, underscores and dashes. International characters are allowed.
     * Label values are optional. Label keys must start with a letter.
     * See https://goo.gl/xmQnxf for more information on and examples of labels.
     *
     * Generated from protobuf field <code>map<string, string> labels = 39;</code>
     */
    private $labels;
    protected $dataset_metadata;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \Google\Cloud\AutoMl\V1\TranslationDatasetMetadata $translation_dataset_metadata
     *           Metadata for a dataset used for translation.
     *     @type \Google\Cloud\AutoMl\V1\ImageClassificationDatasetMetadata $image_classification_dataset_metadata
     *           Metadata for a dataset used for image classification.
     *     @type \Google\Cloud\AutoMl\V1\TextClassificationDatasetMetadata $text_classification_dataset_metadata
     *           Metadata for a dataset used for text classification.
     *     @type \Google\Cloud\AutoMl\V1\ImageObjectDetectionDatasetMetadata $image_object_detection_dataset_metadata
     *           Metadata for a dataset used for image object detection.
     *     @type \Google\Cloud\AutoMl\V1\TextExtractionDatasetMetadata $text_extraction_dataset_metadata
     *           Metadata for a dataset used for text extraction.
     *     @type \Google\Cloud\AutoMl\V1\TextSentimentDatasetMetadata $text_sentiment_dataset_metadata
     *           Metadata for a dataset used for text sentiment.
     *     @type string $name
     *           Output only. The resource name of the dataset.
     *           Form: `projects/{project_id}/locations/{location_id}/datasets/{dataset_id}`
     *     @type string $display_name
     *           Required. The name of the dataset to show in the interface. The name can be
     *           up to 32 characters long and can consist only of ASCII Latin letters A-Z
     *           and a-z, underscores
     *           (_), and ASCII digits 0-9.
     *     @type string $description
     *           User-provided description of the dataset. The description can be up to
     *           25000 characters long.
     *     @type int $example_count
     *           Output only. The number of examples in the dataset.
     *     @type \Google\Protobuf\Timestamp $create_time
     *           Output only. Timestamp when this dataset was created.
     *     @type string $etag
     *           Used to perform consistent read-modify-write updates. If not set, a blind
     *           "overwrite" update happens.
     *     @type array|\Google\Protobuf\Internal\MapField $labels
     *           Optional. The labels with user-defined metadata to organize your dataset.
     *           Label keys and values can be no longer than 64 characters
     *           (Unicode codepoints), can only contain lowercase letters, numeric
     *           characters, underscores and dashes. International characters are allowed.
     *           Label values are optional. Label keys must start with a letter.
     *           See https://goo.gl/xmQnxf for more information on and examples of labels.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Cloud\Automl\V1\Dataset::initOnce();
        parent::__construct($data);
    }

    /**
     * Metadata for a dataset used for translation.
     *
     * Generated from protobuf field <code>.google.cloud.automl.v1.TranslationDatasetMetadata translation_dataset_metadata = 23;</code>
     * @return \Google\Cloud\AutoMl\V1\TranslationDatasetMetadata
     */
    public function getTranslationDatasetMetadata()
    {
        return $this->readOneof(23);
    }

    public function hasTranslationDatasetMetadata()
    {
        return $this->hasOneof(23);
    }

    /**
     * Metadata for a dataset used for translation.
     *
     * Generated from protobuf field <code>.google.cloud.automl.v1.TranslationDatasetMetadata translation_dataset_metadata = 23;</code>
     * @param \Google\Cloud\AutoMl\V1\TranslationDatasetMetadata $var
     * @return $this
     */
    public function setTranslationDatasetMetadata($var)
    {
        GPBUtil::checkMessage($var, \Google\Cloud\AutoMl\V1\TranslationDatasetMetadata::class);
        $this->writeOneof(23, $var);

        return $this;
    }

    /**
     * Metadata for a dataset used for image classification.
     *
     * Generated from protobuf field <code>.google.cloud.automl.v1.ImageClassificationDatasetMetadata image_classification_dataset_metadata = 24;</code>
     * @return \Google\Cloud\AutoMl\V1\ImageClassificationDatasetMetadata
     */
    public function getImageClassificationDatasetMetadata()
    {
        return $this->readOneof(24);
    }

    public function hasImageClassificationDatasetMetadata()
    {
        return $this->hasOneof(24);
    }

    /**
     * Metadata for a dataset used for image classification.
     *
     * Generated from protobuf field <code>.google.cloud.automl.v1.ImageClassificationDatasetMetadata image_classification_dataset_metadata = 24;</code>
     * @param \Google\Cloud\AutoMl\V1\ImageClassificationDatasetMetadata $var
     * @return $this
     */
    public function setImageClassificationDatasetMetadata($var)
    {
        GPBUtil::checkMessage($var, \Google\Cloud\AutoMl\V1\ImageClassificationDatasetMetadata::class);
        $this->writeOneof(24, $var);

        return $this;
    }

    /**
     * Metadata for a dataset used for text classification.
     *
     * Generated from protobuf field <code>.google.cloud.automl.v1.TextClassificationDatasetMetadata text_classification_dataset_metadata = 25;</code>
     * @return \Google\Cloud\AutoMl\V1\TextClassificationDatasetMetadata
     */
    public function getTextClassificationDatasetMetadata()
    {
        return $this->readOneof(25);
    }

    public function hasTextClassificationDatasetMetadata()
    {
        return $this->hasOneof(25);
    }

    /**
     * Metadata for a dataset used for text classification.
     *
     * Generated from protobuf field <code>.google.cloud.automl.v1.TextClassificationDatasetMetadata text_classification_dataset_metadata = 25;</code>
     * @param \Google\Cloud\AutoMl\V1\TextClassificationDatasetMetadata $var
     * @return $this
     */
    public function setTextClassificationDatasetMetadata($var)
    {
        GPBUtil::checkMessage($var, \Google\Cloud\AutoMl\V1\TextClassificationDatasetMetadata::class);
        $this->writeOneof(25, $var);

        return $this;
    }

    /**
     * Metadata for a dataset used for image object detection.
     *
     * Generated from protobuf field <code>.google.cloud.automl.v1.ImageObjectDetectionDatasetMetadata image_object_detection_dataset_metadata = 26;</code>
     * @return \Google\Cloud\AutoMl\V1\ImageObjectDetectionDatasetMetadata
     */
    public function getImageObjectDetectionDatasetMetadata()
    {
        return $this->readOneof(26);
    }

    public function hasImageObjectDetectionDatasetMetadata()
    {
        return $this->hasOneof(26);
    }

    /**
     * Metadata for a dataset used for image object detection.
     *
     * Generated from protobuf field <code>.google.cloud.automl.v1.ImageObjectDetectionDatasetMetadata image_object_detection_dataset_metadata = 26;</code>
     * @param \Google\Cloud\AutoMl\V1\ImageObjectDetectionDatasetMetadata $var
     * @return $this
     */
    public function setImageObjectDetectionDatasetMetadata($var)
    {
        GPBUtil::checkMessage($var, \Google\Cloud\AutoMl\V1\ImageObjectDetectionDatasetMetadata::class);
        $this->writeOneof(26, $var);

        return $this;
    }

    /**
     * Metadata for a dataset used for text extraction.
     *
     * Generated from protobuf field <code>.google.cloud.automl.v1.TextExtractionDatasetMetadata text_extraction_dataset_metadata = 28;</code>
     * @return \Google\Cloud\AutoMl\V1\TextExtractionDatasetMetadata
     */
    public function getTextExtractionDatasetMetadata()
    {
        return $this->readOneof(28);
    }

    public function hasTextExtractionDatasetMetadata()
    {
        return $this->hasOneof(28);
    }

    /**
     * Metadata for a dataset used for text extraction.
     *
     * Generated from protobuf field <code>.google.cloud.automl.v1.TextExtractionDatasetMetadata text_extraction_dataset_metadata = 28;</code>
     * @param \Google\Cloud\AutoMl\V1\TextExtractionDatasetMetadata $var
     * @return $this
     */
    public function setTextExtractionDatasetMetadata($var)
    {
        GPBUtil::checkMessage($var, \Google\Cloud\AutoMl\V1\TextExtractionDatasetMetadata::class);
        $this->writeOneof(28, $var);

        return $this;
    }

    /**
     * Metadata for a dataset used for text sentiment.
     *
     * Generated from protobuf field <code>.google.cloud.automl.v1.TextSentimentDatasetMetadata text_sentiment_dataset_metadata = 30;</code>
     * @return \Google\Cloud\AutoMl\V1\TextSentimentDatasetMetadata
     */
    public function getTextSentimentDatasetMetadata()
    {
        return $this->readOneof(30);
    }

    public function hasTextSentimentDatasetMetadata()
    {
        return $this->hasOneof(30);
    }

    /**
     * Metadata for a dataset used for text sentiment.
     *
     * Generated from protobuf field <code>.google.cloud.automl.v1.TextSentimentDatasetMetadata text_sentiment_dataset_metadata = 30;</code>
     * @param \Google\Cloud\AutoMl\V1\TextSentimentDatasetMetadata $var
     * @return $this
     */
    public function setTextSentimentDatasetMetadata($var)
    {
        GPBUtil::checkMessage($var, \Google\Cloud\AutoMl\V1\TextSentimentDatasetMetadata::class);
        $this->writeOneof(30, $var);

        return $this;
    }

    /**
     * Output only. The resource name of the dataset.
     * Form: `projects/{project_id}/locations/{location_id}/datasets/{dataset_id}`
     *
     * Generated from protobuf field <code>string name = 1;</code>
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Output only. The resource name of the dataset.
     * Form: `projects/{project_id}/locations/{location_id}/datasets/{dataset_id}`
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

    /**
     * Required. The name of the dataset to show in the interface. The name can be
     * up to 32 characters long and can consist only of ASCII Latin letters A-Z
     * and a-z, underscores
     * (_), and ASCII digits 0-9.
     *
     * Generated from protobuf field <code>string display_name = 2;</code>
     * @return string
     */
    public function getDisplayName()
    {
        return $this->display_name;
    }

    /**
     * Required. The name of the dataset to show in the interface. The name can be
     * up to 32 characters long and can consist only of ASCII Latin letters A-Z
     * and a-z, underscores
     * (_), and ASCII digits 0-9.
     *
     * Generated from protobuf field <code>string display_name = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setDisplayName($var)
    {
        GPBUtil::checkString($var, True);
        $this->display_name = $var;

        return $this;
    }

    /**
     * User-provided description of the dataset. The description can be up to
     * 25000 characters long.
     *
     * Generated from protobuf field <code>string description = 3;</code>
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * User-provided description of the dataset. The description can be up to
     * 25000 characters long.
     *
     * Generated from protobuf field <code>string description = 3;</code>
     * @param string $var
     * @return $this
     */
    public function setDescription($var)
    {
        GPBUtil::checkString($var, True);
        $this->description = $var;

        return $this;
    }

    /**
     * Output only. The number of examples in the dataset.
     *
     * Generated from protobuf field <code>int32 example_count = 21;</code>
     * @return int
     */
    public function getExampleCount()
    {
        return $this->example_count;
    }

    /**
     * Output only. The number of examples in the dataset.
     *
     * Generated from protobuf field <code>int32 example_count = 21;</code>
     * @param int $var
     * @return $this
     */
    public function setExampleCount($var)
    {
        GPBUtil::checkInt32($var);
        $this->example_count = $var;

        return $this;
    }

    /**
     * Output only. Timestamp when this dataset was created.
     *
     * Generated from protobuf field <code>.google.protobuf.Timestamp create_time = 14;</code>
     * @return \Google\Protobuf\Timestamp
     */
    public function getCreateTime()
    {
        return isset($this->create_time) ? $this->create_time : null;
    }

    public function hasCreateTime()
    {
        return isset($this->create_time);
    }

    public function clearCreateTime()
    {
        unset($this->create_time);
    }

    /**
     * Output only. Timestamp when this dataset was created.
     *
     * Generated from protobuf field <code>.google.protobuf.Timestamp create_time = 14;</code>
     * @param \Google\Protobuf\Timestamp $var
     * @return $this
     */
    public function setCreateTime($var)
    {
        GPBUtil::checkMessage($var, \Google\Protobuf\Timestamp::class);
        $this->create_time = $var;

        return $this;
    }

    /**
     * Used to perform consistent read-modify-write updates. If not set, a blind
     * "overwrite" update happens.
     *
     * Generated from protobuf field <code>string etag = 17;</code>
     * @return string
     */
    public function getEtag()
    {
        return $this->etag;
    }

    /**
     * Used to perform consistent read-modify-write updates. If not set, a blind
     * "overwrite" update happens.
     *
     * Generated from protobuf field <code>string etag = 17;</code>
     * @param string $var
     * @return $this
     */
    public function setEtag($var)
    {
        GPBUtil::checkString($var, True);
        $this->etag = $var;

        return $this;
    }

    /**
     * Optional. The labels with user-defined metadata to organize your dataset.
     * Label keys and values can be no longer than 64 characters
     * (Unicode codepoints), can only contain lowercase letters, numeric
     * characters, underscores and dashes. International characters are allowed.
     * Label values are optional. Label keys must start with a letter.
     * See https://goo.gl/xmQnxf for more information on and examples of labels.
     *
     * Generated from protobuf field <code>map<string, string> labels = 39;</code>
     * @return \Google\Protobuf\Internal\MapField
     */
    public function getLabels()
    {
        return $this->labels;
    }

    /**
     * Optional. The labels with user-defined metadata to organize your dataset.
     * Label keys and values can be no longer than 64 characters
     * (Unicode codepoints), can only contain lowercase letters, numeric
     * characters, underscores and dashes. International characters are allowed.
     * Label values are optional. Label keys must start with a letter.
     * See https://goo.gl/xmQnxf for more information on and examples of labels.
     *
     * Generated from protobuf field <code>map<string, string> labels = 39;</code>
     * @param array|\Google\Protobuf\Internal\MapField $var
     * @return $this
     */
    public function setLabels($var)
    {
        $arr = GPBUtil::checkMapField($var, \Google\Protobuf\Internal\GPBType::STRING, \Google\Protobuf\Internal\GPBType::STRING);
        $this->labels = $arr;

        return $this;
    }

    /**
     * @return string
     */
    public function getDatasetMetadata()
    {
        return $this->whichOneof("dataset_metadata");
    }

}

