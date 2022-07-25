<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/talent/v4beta1/event.proto

namespace Google\Cloud\Talent\V4beta1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * An event issued when an end user interacts with the application that
 * implements Cloud Talent Solution. Providing this information improves the
 * quality of results for the API clients, enabling the
 * service to perform optimally. The number of events sent must be consistent
 * with other calls, such as job searches, issued to the service by the client.
 *
 * Generated from protobuf message <code>google.cloud.talent.v4beta1.ClientEvent</code>
 */
class ClientEvent extends \Google\Protobuf\Internal\Message
{
    /**
     * Strongly recommended for the best service experience.
     * A unique ID generated in the API responses. It can be found in
     * [ResponseMetadata.request_id][google.cloud.talent.v4beta1.ResponseMetadata.request_id].
     *
     * Generated from protobuf field <code>string request_id = 1;</code>
     */
    private $request_id = '';
    /**
     * Required. A unique identifier, generated by the client application.
     *
     * Generated from protobuf field <code>string event_id = 2 [(.google.api.field_behavior) = REQUIRED];</code>
     */
    private $event_id = '';
    /**
     * Required. The timestamp of the event.
     *
     * Generated from protobuf field <code>.google.protobuf.Timestamp create_time = 4 [(.google.api.field_behavior) = REQUIRED];</code>
     */
    private $create_time = null;
    /**
     * Notes about the event provided by recruiters or other users, for example,
     * feedback on why a profile was bookmarked.
     *
     * Generated from protobuf field <code>string event_notes = 9;</code>
     */
    private $event_notes = '';
    protected $event;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $request_id
     *           Strongly recommended for the best service experience.
     *           A unique ID generated in the API responses. It can be found in
     *           [ResponseMetadata.request_id][google.cloud.talent.v4beta1.ResponseMetadata.request_id].
     *     @type string $event_id
     *           Required. A unique identifier, generated by the client application.
     *     @type \Google\Protobuf\Timestamp $create_time
     *           Required. The timestamp of the event.
     *     @type \Google\Cloud\Talent\V4beta1\JobEvent $job_event
     *           An event issued when a job seeker interacts with the application that
     *           implements Cloud Talent Solution.
     *     @type \Google\Cloud\Talent\V4beta1\ProfileEvent $profile_event
     *           An event issued when a profile searcher interacts with the application
     *           that implements Cloud Talent Solution.
     *     @type string $event_notes
     *           Notes about the event provided by recruiters or other users, for example,
     *           feedback on why a profile was bookmarked.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Cloud\Talent\V4Beta1\Event::initOnce();
        parent::__construct($data);
    }

    /**
     * Strongly recommended for the best service experience.
     * A unique ID generated in the API responses. It can be found in
     * [ResponseMetadata.request_id][google.cloud.talent.v4beta1.ResponseMetadata.request_id].
     *
     * Generated from protobuf field <code>string request_id = 1;</code>
     * @return string
     */
    public function getRequestId()
    {
        return $this->request_id;
    }

    /**
     * Strongly recommended for the best service experience.
     * A unique ID generated in the API responses. It can be found in
     * [ResponseMetadata.request_id][google.cloud.talent.v4beta1.ResponseMetadata.request_id].
     *
     * Generated from protobuf field <code>string request_id = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setRequestId($var)
    {
        GPBUtil::checkString($var, True);
        $this->request_id = $var;

        return $this;
    }

    /**
     * Required. A unique identifier, generated by the client application.
     *
     * Generated from protobuf field <code>string event_id = 2 [(.google.api.field_behavior) = REQUIRED];</code>
     * @return string
     */
    public function getEventId()
    {
        return $this->event_id;
    }

    /**
     * Required. A unique identifier, generated by the client application.
     *
     * Generated from protobuf field <code>string event_id = 2 [(.google.api.field_behavior) = REQUIRED];</code>
     * @param string $var
     * @return $this
     */
    public function setEventId($var)
    {
        GPBUtil::checkString($var, True);
        $this->event_id = $var;

        return $this;
    }

    /**
     * Required. The timestamp of the event.
     *
     * Generated from protobuf field <code>.google.protobuf.Timestamp create_time = 4 [(.google.api.field_behavior) = REQUIRED];</code>
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
     * Required. The timestamp of the event.
     *
     * Generated from protobuf field <code>.google.protobuf.Timestamp create_time = 4 [(.google.api.field_behavior) = REQUIRED];</code>
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
     * An event issued when a job seeker interacts with the application that
     * implements Cloud Talent Solution.
     *
     * Generated from protobuf field <code>.google.cloud.talent.v4beta1.JobEvent job_event = 5;</code>
     * @return \Google\Cloud\Talent\V4beta1\JobEvent
     */
    public function getJobEvent()
    {
        return $this->readOneof(5);
    }

    public function hasJobEvent()
    {
        return $this->hasOneof(5);
    }

    /**
     * An event issued when a job seeker interacts with the application that
     * implements Cloud Talent Solution.
     *
     * Generated from protobuf field <code>.google.cloud.talent.v4beta1.JobEvent job_event = 5;</code>
     * @param \Google\Cloud\Talent\V4beta1\JobEvent $var
     * @return $this
     */
    public function setJobEvent($var)
    {
        GPBUtil::checkMessage($var, \Google\Cloud\Talent\V4beta1\JobEvent::class);
        $this->writeOneof(5, $var);

        return $this;
    }

    /**
     * An event issued when a profile searcher interacts with the application
     * that implements Cloud Talent Solution.
     *
     * Generated from protobuf field <code>.google.cloud.talent.v4beta1.ProfileEvent profile_event = 6;</code>
     * @return \Google\Cloud\Talent\V4beta1\ProfileEvent
     */
    public function getProfileEvent()
    {
        return $this->readOneof(6);
    }

    public function hasProfileEvent()
    {
        return $this->hasOneof(6);
    }

    /**
     * An event issued when a profile searcher interacts with the application
     * that implements Cloud Talent Solution.
     *
     * Generated from protobuf field <code>.google.cloud.talent.v4beta1.ProfileEvent profile_event = 6;</code>
     * @param \Google\Cloud\Talent\V4beta1\ProfileEvent $var
     * @return $this
     */
    public function setProfileEvent($var)
    {
        GPBUtil::checkMessage($var, \Google\Cloud\Talent\V4beta1\ProfileEvent::class);
        $this->writeOneof(6, $var);

        return $this;
    }

    /**
     * Notes about the event provided by recruiters or other users, for example,
     * feedback on why a profile was bookmarked.
     *
     * Generated from protobuf field <code>string event_notes = 9;</code>
     * @return string
     */
    public function getEventNotes()
    {
        return $this->event_notes;
    }

    /**
     * Notes about the event provided by recruiters or other users, for example,
     * feedback on why a profile was bookmarked.
     *
     * Generated from protobuf field <code>string event_notes = 9;</code>
     * @param string $var
     * @return $this
     */
    public function setEventNotes($var)
    {
        GPBUtil::checkString($var, True);
        $this->event_notes = $var;

        return $this;
    }

    /**
     * @return string
     */
    public function getEvent()
    {
        return $this->whichOneof("event");
    }

}

