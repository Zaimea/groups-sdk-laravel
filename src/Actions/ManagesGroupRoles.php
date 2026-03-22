<?php

namespace Zaimea\SDK\Groups\Actions;

use Zaimea\SDK\Groups\Resources\Permission;
use Zaimea\SDK\Groups\Resources\Role;
use Zaimea\SDK\Groups\Resources\Response;

trait ManagesGroupRoles
{
    /**
     * Get a group role instance.
     *
     * @param  int  $groupId
     * @param  int  $roleId
     * @return \Zaimea\SDK\Groups\Resources\Role
     */
    public function role(int $groupId, int $roleId): Role
    {
        return new Role(
            $this->get("roles/read", ['group' => $groupId, 'roleId' =>  $roleId])['data']
            + ['groupId' => $groupId], $this
        );
    }

    /**
     * Get paginated group roles.
     *
     * @param  int  $groupId
     * @param  array  $filters ['search' => 'key / name / description / permissions', 'default' => bool]
     * @param  int  $page
     * @return \Zaimea\SDK\Groups\Resources\Role[]
     */
    public function roles($groupId, array $filters = [], int $page = 1)
    {
        $params = array_merge(
            ['group' => $groupId, 'page' => $page],
            $filters
        );

        return $this->transformCollection(
            $this->get("roles/all", $params)['roles'],
            Role::class,
            ['groupId' => $groupId]
        );
    }

    /**
     * Get a group role instance.
     *
     * @param  int  $groupId
     * @param  int  $roleId
     * @return array
     */
    public function rolePermissions(int $groupId, int $roleId): array
    {
        return $this->get("roles/read/permissions", ['group' => $groupId, 'roleId' =>  $roleId]);
    }

    /**
     * Create a new group role.
     * 
     * @param  int  $groupId
     * @param  array  $data ['client' => bool, 'name' => string, 'description' => string, 'status' => bool, 'permissions' => array]
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function createRole(int $groupId, array $data)
    {
        $params = array_merge(
            ['group' => $groupId],
            $data
        );
        
        return new Response($this->post("roles/create", $params), $this);
    }

    /**
     * Update the given group role.
     * 
     * @param  int  $groupId
     * @param  array  $data ['roleId' => int, 'client' => bool, 'name' => string, 'description' => string, 'status' => bool]
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function updateRole(int $groupId, array $data)
    {
        $params = array_merge(
            ['group' => $groupId],
            $data
        );

        return new Response($this->put("roles/update", $params), $this);
    }

    /**
     * Get paginated group role permissions.
     *
     * @param  int  $groupId
     * @param  array  $filters ['search' => 'by title]
     * @param  int  $page
     * @return \Zaimea\SDK\Groups\Resources\Permission[]
     */
    public function groupPermissions($groupId, array $filters = [], int $page = 1)
    {
        $params = array_merge(
            ['group' => $groupId, 'page' => $page],
            $filters
        );

        return $this->transformCollection(
            $this->get("roles/permissions", $params)['permissions'],
            Permission::class,
            ['groupId' => $groupId]
        );
    }

    /**
     * Update the given group role permissions.
     * 
     * @param  int  $groupId
     * @param  array  $data ['roleId' => int, 'permissions' => array]
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function updateRolePermissions(int $groupId, array $data)
    {
        $params = array_merge(
            ['group' => $groupId],
            $data
        );

        return new Response($this->put("roles/update/permissions", $params), $this);
    }

    /**
     * Delete the given group role.
     *
     * @param  int  $groupId
     * @param  int  $roleId
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function deleteRole(int $groupId, int $roleId)
    {
        return new Response($this->delete("roles/delete", [
            'group' => $groupId, 
            'roleId' => $roleId,
        ]), $this);
    }
}