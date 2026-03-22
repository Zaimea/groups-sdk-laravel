<?php

namespace Zaimea\SDK\Groups\Actions;

use Zaimea\SDK\Groups\Resources\Color;
use Zaimea\SDK\Groups\Resources\Response;

trait ManagesGroupColors
{
    /**
     * Get a group color instance.
     *
     * @param  int  $groupId
     * @param  int  $colorId
     * @return \Zaimea\SDK\Groups\Resources\Color
     */
    public function color(int $groupId, int $colorId)
    {
        return new Color(
            $this->get("colors/read", ['group' => $groupId, 'colorId' => $colorId])['data'], $this
        );
    }

    /**
     * Get the collection of group colors.
     *
     * @return \Zaimea\SDK\Groups\Resources\Color[]
     */
    public function colors($groupId)
    {
        return $this->transformCollection(
            $this->get("colors/all", ['group' => $groupId])['data'],
            Color::class,
        );
    }

    /**
     * Create a new group color.
     *
     * @param  int  $groupId
     * @param  array $data ['name' => '', 'color_licht' => 'red', 'color_licht_value' => 200, 'color_dark' => 'red', 'color_dark_value' => 400]
     * @return \Zaimea\SDK\Groups\Resources\Color
     */
    public function createColor(int $groupId, array $data)
    {
        $params = array_merge(
            ['group' => $groupId],
            $data
        );
        
        $color = $this->post("colors/store", $params);

        return new Color($color, $this);
    }

    /**
     * Update a group color.
     * 
     * @param  int   $groupId
     * @param  array $data ['colorId' => '', 'name'=> '', 'color_licht' => 'red', 'color_licht_value' => 200, 'color_dark' => 'red', 'color_dark_value' => 400]
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function updateColor(int $groupId, array $data)
    {
        $params = array_merge(
            ['group' => $groupId],
            $data,
        );

        return new Response(
            $this->put("colors/update", $params)
            + ['groupId' => $groupId], $this
        );
    }

    /**
     * Delete the given group color.
     *
     * @param  int  $groupId
     * @param  int  $colorId
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function deleteColor(int $groupId, int $colorId)
    {
        return new Response(
            $this->delete("colors/delete", ['group' => $groupId, 'colorId' => $colorId]), $this
        );
    }
}