<?php

declare(strict_types=1);

namespace Zaimea\SDK\Groups\Actions;

use Zaimea\SDK\Groups\Resources\Resource;
use Zaimea\SDK\Groups\Resources\Response;

trait ManagesGroupReports
{
    /**
     * Get a group report fields instance.
     *
     * @param  int  $groupId
     * @return \Zaimea\SDK\Groups\Resources\Resource
     */
    public function reportFields(int $groupId): Resource
    {
        return new Resource(
            $this->get("reports/fields", ['group' => $groupId])['data'], $this
        );
    }
    
    /**
     * Generate a group report instance.
     * 
     * @param  int  $groupId
     * @param  array  $data [
     *                          'options' => [
     *                              'period'       => 'thisDay', // "thisWeek" / "thisMonth" / "previousMonth" / "thisYear'
     *                              'period_start' => 'YYYY-MM-DD',
     *                              'period_end'   => 'YYYY-MM-DD',
     *                              'users'        => ['1', '2'],
     *                              'client'       => '',
     *                              'projects'     => ['1', '2'],
     *                              'approved'     => '1',
     *                          ],
     *                          'checkOptions' => [
     *                              'checkClient'   => true,
     *                              'checkProject'  => false,
     *                              'checkStart'    => true,
     *                              'checkFinish'   => true,
     *                              'checkPause'    => true,
     *                              'checkDuration' => true,
     *                              'checkWorked'   => true,
     *                              'checkNote'     => false,
     *                              'checkType'     => false,
     *                              'checkApproved' => false,
     *                              'groupBy'       => 'date', // "user" / "project" / "client"
     *                          ]
     *                      ]
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function reportGenerate(int $groupId, array $data): Response
    {
        $params = array_merge(
            ['group' => $groupId],
            $data
        );
        
        $response = $this->get("reports/generate", $params);
        
        // Daca raspunsul este un string (PDF content), il impachetam intr-un array
        if (is_string($response)) {
            $response = ['content' => $response, 'type' => 'pdf'];
        }
        
        return new Response($response, $this);
    }
    public function reportGenerate2(int $groupId, array $data)
    {
        $params = array_merge(
            ['group' => $groupId],
            $data
        );
        // TODO: fix becouse it return direct PDF file via API
        return new Response($this->get("reports/generate", $params), $this);
    }
}