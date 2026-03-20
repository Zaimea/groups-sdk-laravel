<?php

declare(strict_types=1);

namespace Zaimea\SDK\Groups\Resources;

class GroupMount extends Resource
{
    /**
     * The id of the group.
     *
     * @var int
     */
    public $groupId;

    /**
     * The state of the group.
     *
     * @var array
     */
    public $group;

    /**
     * The plugins of the group.
     *
     * @var array
     */
    public $plugins;

    /**
     * The confings of the group.
     *
     * @var array
     */
    public $configs;

    /**
     * The selected plugins of the group.
     *
     * @var array
     */
    public $pluginsSelected;

    /**
     * The selected configs of the group.
     *
     * @var array
     */
    public $configsSelected;

    /**
     * Get if project is required on this group.
     *
     * @var boolean
     */
    public $projectRequired;
}