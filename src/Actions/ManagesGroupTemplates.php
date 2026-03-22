<?php

namespace Zaimea\SDK\Groups\Actions;

use Zaimea\SDK\Groups\Resources\Project;
use Zaimea\SDK\Groups\Resources\Template;
use Zaimea\SDK\Groups\Resources\Response;

trait ManagesGroupTemplates
{
    /**
     * Get a group template instance.
     *
     * @param  int  $groupId
     * @param  int  $templateId
     * @return \Zaimea\SDK\Groups\Resources\Template
     */
    public function template(int $groupId, int $templateId): Template
    {
        return new Template(
            $this->get("templates/read", ['group' => $groupId, 'templateId' =>  $templateId])['data'], $this
        );
    }

    /**
     * Get paginated group templates.
     * 
     * @param  int  $groupId
     * @param  array  $filters = ['search' => 'title / description / created at']
     * @param  int  $page
     * @return \Zaimea\SDK\Groups\Resources\Template[]
     */
    public function templates($groupId, array $filters = [], int $page = 1)
    {
        $params = array_merge(
            ['group' => $groupId, 'page' => $page],
            $filters
        );

        return $this->transformCollection(
            $this->get("templates/all", $params)['templates'],
            Template::class,
            ['groupId' => $groupId]
        );
    }

    /**
     * Create a new group template.
     * 
     * @param  int  $groupId
     * @param  array  $data [
     *                          'title'       => 'title',
     *                          'description' => 'description',
     *                          'content'     => 'content',
     *                          'projects'    => [1,2],  // projects Ids
     *                          'status'      => 1,      // 1 = activ, 0 = inactiv
     *                      ]
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function createGroupProjectTemplate(int $groupId, array $data)
    {
        $params = array_merge(
            ['group' => $groupId],
            $data
        );
        
        return new Response($this->post("templates/create", $params), $this);
    }

    /**
     * Get paginated group template projects.
     * 
     * @param  int  $groupId
     * @param  int  $templateId
     * @return \Zaimea\SDK\Groups\Resources\Project[]
     */
    public function templateProjects($groupId, int $templateId)
    {
        $params = array_merge(
            ['group' => $groupId, 'templateId' => $templateId],
        );
        
        return $this->transformCollection(
            $this->get("templates/read/projects", $params)['data'],
            Project::class,
            ['groupId' => $groupId]
        );
    }
    
    /**
     * Update the given group project.
     * 
     * @param  int  $groupId
     * @param  int  $templateId
     * @param  array  $data [
     *                          'title'       => 'title',
     *                          'description' => 'description',
     *                          'content'     => 'content',
     *                          'status'      => 1,  // 1 = activ, 0 = inactiv
     *                      ]
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function updateTemplate(int $groupId, int $templateId, array $data)
    {
        $params = array_merge(
            ['group' => $groupId, 'templateId' => $templateId],
            $data
        );

        return new Response($this->put("templates/update", $params), $this);
    }
    
    /**
     * Update the given group template projects.
     * 
     * @param  int  $groupId
     * @param  int  $templateId
     * @param  array  $data ['projects' => ['1', '2']]
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function updateTemplateProjects(int $groupId, int $templateId, array $data)
    {
        $params = array_merge(
            ['group' => $groupId, 'templateId' => $templateId],
            $data
        );

        return new Response($this->put("templates/update/projects", $params), $this);
    }

    /**
     * Delete the given group template.
     *
     * @param  int  $groupId
     * @param  int  $templateId
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function deleteGroupTemplate(int $groupId, int $templateId)
    {
        return new Response($this->delete("templates/delete", [
            'group' => $groupId, 
            'templateId' => $templateId,
        ]), $this);
    }
}