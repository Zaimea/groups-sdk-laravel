<?php

declare(strict_types=1);

namespace Zaimea\SDK\Groups\Facades;

use Illuminate\Support\Facades\Facade;
use Zaimea\SDK\Groups\GroupsClient;

/**
 * @method static array getGroups()
 * @method static array getGroupRecords(array $filters = [])
 * @method static array createGroup(string $name, array $options = [])
 * @method static array getGroup(int $groupId)
 * @method static array updateGroup(int $groupId, array $data)
 * @method static array deleteGroup(int $groupId)
 * @method static array getGroupMembers(int $groupId)
 * @method static array getGroupProjects(int $groupId)
 * @method static \Zaimea\SDK\Groups\GroupsClient setToken(string $token)
 */
class Groups extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'groups';
    }
}