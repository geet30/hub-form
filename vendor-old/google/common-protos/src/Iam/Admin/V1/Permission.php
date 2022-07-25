<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/iam/admin/v1/iam.proto

namespace Google\Iam\Admin\V1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * A permission which can be included by a role.
 *
 * Generated from protobuf message <code>google.iam.admin.v1.Permission</code>
 */
class Permission extends \Google\Protobuf\Internal\Message
{
    /**
     * The name of this Permission.
     *
     * Generated from protobuf field <code>string name = 1;</code>
     */
    private $name = '';
    /**
     * The title of this Permission.
     *
     * Generated from protobuf field <code>string title = 2;</code>
     */
    private $title = '';
    /**
     * A brief description of what this Permission is used for.
     * This permission can ONLY be used in predefined roles.
     *
     * Generated from protobuf field <code>string description = 3;</code>
     */
    private $description = '';
    /**
     * This permission can ONLY be used in predefined roles.
     *
     * Generated from protobuf field <code>bool only_in_predefined_roles = 4;</code>
     */
    private $only_in_predefined_roles = false;
    /**
     * The current launch stage of the permission.
     *
     * Generated from protobuf field <code>.google.iam.admin.v1.Permission.PermissionLaunchStage stage = 5;</code>
     */
    private $stage = 0;
    /**
     * The current custom role support level.
     *
     * Generated from protobuf field <code>.google.iam.admin.v1.Permission.CustomRolesSupportLevel custom_roles_support_level = 6;</code>
     */
    private $custom_roles_support_level = 0;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $name
     *           The name of this Permission.
     *     @type string $title
     *           The title of this Permission.
     *     @type string $description
     *           A brief description of what this Permission is used for.
     *           This permission can ONLY be used in predefined roles.
     *     @type bool $only_in_predefined_roles
     *           This permission can ONLY be used in predefined roles.
     *     @type int $stage
     *           The current launch stage of the permission.
     *     @type int $custom_roles_support_level
     *           The current custom role support level.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Iam\Admin\V1\Iam::initOnce();
        parent::__construct($data);
    }

    /**
     * The name of this Permission.
     *
     * Generated from protobuf field <code>string name = 1;</code>
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * The name of this Permission.
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
     * The title of this Permission.
     *
     * Generated from protobuf field <code>string title = 2;</code>
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * The title of this Permission.
     *
     * Generated from protobuf field <code>string title = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setTitle($var)
    {
        GPBUtil::checkString($var, True);
        $this->title = $var;

        return $this;
    }

    /**
     * A brief description of what this Permission is used for.
     * This permission can ONLY be used in predefined roles.
     *
     * Generated from protobuf field <code>string description = 3;</code>
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * A brief description of what this Permission is used for.
     * This permission can ONLY be used in predefined roles.
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
     * This permission can ONLY be used in predefined roles.
     *
     * Generated from protobuf field <code>bool only_in_predefined_roles = 4;</code>
     * @return bool
     */
    public function getOnlyInPredefinedRoles()
    {
        return $this->only_in_predefined_roles;
    }

    /**
     * This permission can ONLY be used in predefined roles.
     *
     * Generated from protobuf field <code>bool only_in_predefined_roles = 4;</code>
     * @param bool $var
     * @return $this
     */
    public function setOnlyInPredefinedRoles($var)
    {
        GPBUtil::checkBool($var);
        $this->only_in_predefined_roles = $var;

        return $this;
    }

    /**
     * The current launch stage of the permission.
     *
     * Generated from protobuf field <code>.google.iam.admin.v1.Permission.PermissionLaunchStage stage = 5;</code>
     * @return int
     */
    public function getStage()
    {
        return $this->stage;
    }

    /**
     * The current launch stage of the permission.
     *
     * Generated from protobuf field <code>.google.iam.admin.v1.Permission.PermissionLaunchStage stage = 5;</code>
     * @param int $var
     * @return $this
     */
    public function setStage($var)
    {
        GPBUtil::checkEnum($var, \Google\Iam\Admin\V1\Permission_PermissionLaunchStage::class);
        $this->stage = $var;

        return $this;
    }

    /**
     * The current custom role support level.
     *
     * Generated from protobuf field <code>.google.iam.admin.v1.Permission.CustomRolesSupportLevel custom_roles_support_level = 6;</code>
     * @return int
     */
    public function getCustomRolesSupportLevel()
    {
        return $this->custom_roles_support_level;
    }

    /**
     * The current custom role support level.
     *
     * Generated from protobuf field <code>.google.iam.admin.v1.Permission.CustomRolesSupportLevel custom_roles_support_level = 6;</code>
     * @param int $var
     * @return $this
     */
    public function setCustomRolesSupportLevel($var)
    {
        GPBUtil::checkEnum($var, \Google\Iam\Admin\V1\Permission_CustomRolesSupportLevel::class);
        $this->custom_roles_support_level = $var;

        return $this;
    }

}

