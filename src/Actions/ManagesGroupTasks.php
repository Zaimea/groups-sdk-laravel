<?php

namespace Zaimea\SDK\Groups\Actions;

use Zaimea\SDK\Groups\Resources\Project;
use Zaimea\SDK\Groups\Resources\Task;
use Zaimea\SDK\Groups\Resources\Response;

trait ManagesGroupTasks
{
    /**
     * Get a group task instance.
     *
     * @param  int  $groupId
     * @param  int  $taskId
     * @return \Zaimea\SDK\Groups\Resources\Task
     */
    public function task(int $groupId, int $taskId): Task
    {
        return new Task(
            $this->get("tasks/read", ['group' => $groupId, 'taskId' =>  $taskId])['data'], $this
        );
    }

    /**
     * Get paginated group tasks.
     * 
     * @param  int  $groupId
     * @param  array  $filters = ['search' => 'title / description / created at']
     * @param  int  $page
     * @return \Zaimea\SDK\Groups\Resources\Task[]
     */
    public function tasks($groupId, array $filters = [], int $page = 1)
    {
        $params = array_merge(
            ['group' => $groupId, 'page' => $page],
            $filters
        );

        return $this->transformCollection(
            $this->get("tasks/all", $params)['tasks'],
            Task::class,
            ['groupId' => $groupId]
        );
    }

    /**
     * Create a new group task.
     *
     * @param  int  $groupId
     * @param  array  $data [
     *                          'title'       => 'title',
     *                          'description' => 'description',
     *                          'projects'    => [1,2],  // projects Ids
     *                          'status'      => 1,      // 1 = activ, 0 = inactiv
     *                      ]
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function createProjectTask(int $groupId, array $data)
    {
        $params = array_merge(
            ['group' => $groupId],
            $data
        );
        
        return new Response($this->post("tasks/create", $params), $this);
    }

    /**
     * Get paginated group task projects.
     * 
     * @param  int  $groupId
     * @param  int  $taskId
     * @return \Zaimea\SDK\Groups\Resources\Project[]
     */
    public function taskProjects($groupId, int $taskId)
    {
        $params = array_merge(
            ['group' => $groupId, 'taskId' => $taskId],
        );
        
        return $this->transformCollection(
            $this->get("tasks/read/projects", $params)['data'],
            Project::class,
            ['groupId' => $groupId]
        );
    }
    
    /**
     * Update the given group project.
     * 
     * @param  int  $groupId
     * @param  int  $taskId
     * @param  array  $data [
     *                          'title'       => 'title',
     *                          'description' => 'description',
     *                          'status'      => 1,  // 1 = activ, 0 = inactiv
     *                      ]
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function updateTask(int $groupId, int $taskId, array $data)
    {
        $params = array_merge(
            ['group' => $groupId, 'taskId' => $taskId],
            $data
        );

        return new Response($this->put("tasks/update", $params), $this);
    }
    
    /**
     * Update the given group task projects.
     * 
     * @param  int  $groupId
     * @param  int  $taskId
     * @param  array  $data ['projects' => ['1', '2']]
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function updateTaskProjects(int $groupId, int $taskId, array $data)
    {
        $params = array_merge(
            ['group' => $groupId, 'taskId' => $taskId],
            $data
        );

        return new Response($this->put("tasks/update/projects", $params), $this);
    }

    /**
     * Delete the given group task.
     *
     * @param  int  $groupId
     * @param  int  $taskId
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function deleteTask(int $groupId, int $taskId)
    {
        return new Response($this->delete("tasks/delete", [
            'group' => $groupId, 
            'taskId' => $taskId,
        ]), $this);
    }
}