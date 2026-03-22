<?php

declare(strict_types=1);

namespace Zaimea\SDK\Groups\Actions;

use Zaimea\SDK\Groups\Resources\MonthlyQuotas;
use Zaimea\SDK\Groups\Resources\Response;

trait ManagesGroupMonthlyQuotas
{
    /**
     * Get a group member instance.
     *
     * @param  int  $groupId
     * @param  array  $data ['year' => 2026]
     * @return \Zaimea\SDK\Groups\Resources\MonthlyQuotas
     */
    public function monthlyQuotas(int $groupId, array $data): MonthlyQuotas
    {
        $params = array_merge(
            ['group' => $groupId],
            $data
        );
        
        return new MonthlyQuotas(
            $this->get("monthly-quotas/read", $params)
            + ['groupId' => $groupId], $this
        );
    }

    /**
     * Update the given group member role.
     * 
     * @param  int  $groupId
     *                                                                                   "1" = Month     "2" = Month
     * @param  array  $data ['workday_minutes' => '07:00', 'year' => 2026, 'minutes' => ["1" => "06:00", "2" => "07:00"]]
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function updateOrCreateMonthlyQuotas(int $groupId, array $data)
    {
        $params = array_merge(
            ['group' => $groupId],
            $data
        );

        return new Response($this->put("monthly-quotas/create", $params), $this);
    }
}