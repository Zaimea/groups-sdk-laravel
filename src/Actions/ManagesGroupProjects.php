<?php

namespace Zaimea\SDK\Groups\Actions;

use Zaimea\SDK\Groups\Resources\Project;
use Zaimea\SDK\Groups\Resources\Response;

trait ManagesGroupProjects
{
    /**
     * Get a group project instance.
     *
     * @param  int  $groupId
     * @param  int  $projectId
     * @return \Zaimea\SDK\Groups\Resources\Project
     */
    public function project(int $groupId, int $projectId): Project
    {
        return new Project(
            $this->get("projects/read", ['group' => $groupId, 'projectId' =>  $projectId])['data'], $this
        );
    }

    /**
     * Get paginated group projects.
     * 
     * @param  int  $groupId
     * @param  array  $filters ['search' => 'title']
     * @param  int  $page
     * @return \Zaimea\SDK\Groups\Resources\Project[]
     */
    public function projects($groupId, array $filters = [], int $page = 1)
    {
        $params = array_merge(
            ['group' => $groupId, 'page' => $page],
            $filters
        );

        return $this->transformCollection(
            $this->get("projects/all", $params)['projects'],
            Project::class,
            ['groupId' => $groupId]
        );
    }

    /**
     * Get paginated group project Clients.
     * 
     * @param  int  $groupId
     * @param  int  $projectId
     * @return \Zaimea\SDK\Groups\Resources\Client[]
     */
    public function projectClients($groupId, int $projectId)
    {
        $params = array_merge(
            ['group' => $groupId, 'projectId' => $projectId],
        );
        return $this->get("projects/clients", $params);
        /*
        return $this->transformCollection(
            $this->get("projects/clients", $params)['clients'],
            Client::class,
            ['groupId' => $groupId]
        );
        */
    }

    /**
     * Create a new group project.
     *
     * @param  int  $groupId
     * @param  array  $data ['form' => [
     *                          'title'                 => 'title',
     *                          'description'           => 'description',
     *                          'users'                 => [1,2],  // users Ids
     *                          'clients'               => [],     // clients Ids
     *                          'tasks'                 => [],     // tasks Ids
     *                          'templates'             => [],     // templates Ids
     *                          'work_pause'            => [
     *                              'work' => ['time_1' => '08:00', 'time_2' => '12:00', 'time_3' => '13:00', 'time_4' => '17:00'],
     *                              'pause' => ['time_1' => '12:00', 'time_2' => '13:00', 'time_3' => null, 'time_4' => null]
     *                          ],
     *                          'extra_pay'             => [
     *                              'above_tariff'    => ['bonus' => '10'],
     *                              'saturday'        => ['bonus' => '50', 'start' => '00:00', 'end' => '23:59'],
     *                              'sunday'          => ['bonus' => '100', 'start' => '00:00', 'end' => '23:59'],
     *                              'morning_shift'   => ['bonus' => '5', 'start' => '06:00', 'end' => '14:00'],
     *                              'afternoon_shift' => ['bonus' => '5', 'start' => '14:00', 'end' => '22:00'],
     *                              'night_shift'     => ['bonus' => '20', 'start' => '22:00', 'end' => '06:00'],
     *                              'holiday'         => ['bonus' => '100', 'start' => '00:00', 'end' => '23:59'],
     *                          ],
     *                          'shift_models'          => '{}',
     *                          'status'                => 1,     // 1 = activ, 0 = inactiv
     *                      ]]
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function createProject(int $groupId, array $data)
    {
        $params = array_merge(
            ['group' => $groupId],
            $data
        );
        
        return new Response($this->post("projects/create", $params), $this);
    }

    /**
     * Get paginated group project tasks.
     * 
     * @param  int  $groupId
     * @param  int  $projectId
     * @return \Zaimea\SDK\Groups\Resources\Project[]
     */
    public function projectTasks($groupId, int $projectId)
    {
        $params = array_merge(
            ['group' => $groupId, 'projectId' => $projectId],
        );
        return $this->get("projects/tasks", $params);
        /*
        return $this->transformCollection(
            $this->get("projects/tasks", $params)['tasks'],
            Client::class,
            ['groupId' => $groupId]
        );
        */
    }

    /**
     * Get paginated group project users.
     * 
     * @param  int  $groupId
     * @param  int  $projectId
     * @return \Zaimea\SDK\Groups\Resources\Project[]
     */
    public function projectUsers($groupId, int $projectId)
    {
        $params = array_merge(
            ['group' => $groupId, 'projectId' => $projectId],
        );
        return $this->get("projects/users", $params);
        /*
        return $this->transformCollection(
            $this->get("projects/users", $params)['users'],
            Client::class,
            ['groupId' => $groupId]
        );
        */
    }

    /**
     * Get paginated group project templates.
     * 
     * @param  int  $groupId
     * @param  int  $projectId
     * @return \Zaimea\SDK\Groups\Resources\Project[]
     */
    public function projectTemplates($groupId, int $projectId)
    {
        $params = array_merge(
            ['group' => $groupId, 'projectId' => $projectId],
        );
        return $this->get("projects/templates", $params);
        /*
        return $this->transformCollection(
            $this->get("projects/templates", $params)['templates'],
            Client::class,
            ['groupId' => $groupId]
        );
        */
    }
    
    /**
     * Update the given group project.
     * 
     * @param  int  $groupId
     * @param  int  $projectId
     * @param  array  $data ['form' => [
     *                          'title'                 => 'title',
     *                          'description'           => 'description',
     *                          'work_pause'            => [
     *                              'work' => ['time_1' => '08:00', 'time_2' => '12:00', 'time_3' => '13:00', 'time_4' => '17:00'],
     *                              'pause' => ['time_1' => '12:00', 'time_2' => '13:00', 'time_3' => null, 'time_4' => null]
     *                          ],
     *                          'extra_pay'             => [
     *                              'above_tariff'    => ['bonus' => '10'],
     *                              'saturday'        => ['bonus' => '50', 'start' => '00:00', 'end' => '23:59'],
     *                              'sunday'          => ['bonus' => '100', 'start' => '00:00', 'end' => '23:59'],
     *                              'morning_shift'   => ['bonus' => '5', 'start' => '06:00', 'end' => '14:00'],
     *                              'afternoon_shift' => ['bonus' => '5', 'start' => '14:00', 'end' => '22:00'],
     *                              'night_shift'     => ['bonus' => '20', 'start' => '22:00', 'end' => '06:00'],
     *                              'holiday'         => ['bonus' => '100', 'start' => '00:00', 'end' => '23:59'],
     *                          ],
     *                          'shift_models'          => '{}',
     *                          'status'                => 1,     // 1 = activ, 0 = inactiv
     *                      ]]
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function updateProject(int $groupId, int $projectId, array $data)
    {
        $params = array_merge(
            ['group' => $groupId, 'projectId' => $projectId],
            $data
        );

        return new Response($this->put("projects/update", $params), $this);
    }
    
    /**
     * Update the given group project clients.
     * 
     * @param  int  $groupId
     * @param  int  $projectId
     * @param  array  $data ['form' => [
     *                          'clients' => ['1', '2'],
     *                      ]]
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function updateProjectClients(int $groupId, int $projectId, array $data)
    {
        $params = array_merge(
            ['group' => $groupId, 'projectId' => $projectId],
            $data
        );

        return new Response($this->put("projects/update-clients", $params), $this);
    }
    
    /**
     * Update the given group project tasks.
     * 
     * @param  int  $groupId
     * @param  int  $projectId
     * @param  array  $data ['form' => [
     *                          'tasks' => ['1', '2'],
     *                      ]]
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function updateProjectTasks(int $groupId, int $projectId, array $data)
    {
        $params = array_merge(
            ['group' => $groupId, 'projectId' => $projectId],
            $data
        );

        return new Response($this->put("projects/update-tasks", $params), $this);
    }
    
    /**
     * Update the given group project templates.
     * 
     * @param  int  $groupId
     * @param  int  $projectId
     * @param  array  $data ['form' => [
     *                          'templates' => ['1', '2'],
     *                      ]]
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function updateProjectTemplates(int $groupId, int $projectId, array $data)
    {
        $params = array_merge(
            ['group' => $groupId, 'projectId' => $projectId],
            $data
        );

        return new Response($this->put("projects/update-templates", $params), $this);
    }
    
    /**
     * Update the given group project users.
     * 
     * @param  int  $groupId
     * @param  int  $projectId
     * @param  array  $data ['form' => [
     *                          'users' => ['1', '2'],
     *                      ]]
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function updateProjectUsers(int $groupId, int $projectId, array $data)
    {
        $params = array_merge(
            ['group' => $groupId, 'projectId' => $projectId],
            $data
        );

        return new Response($this->put("projects/update-users", $params), $this);
    }

    /**
     * Delete the given group project.
     *
     * @param  int  $groupId
     * @param  int  $projectId
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function deleteProject(int $groupId, int $projectId)
    {
        return new Response($this->delete("projects/delete", [
            'group' => $groupId, 
            'projectId' => $projectId,
        ]), $this);
    }
}