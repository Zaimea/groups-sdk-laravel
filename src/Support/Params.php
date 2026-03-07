<?php

declare(strict_types=1);

namespace Zaimea\SDK\Groups\Support;

/**
 * Helper for building parameters in the format expected by the API
 */
class Params
{
    /**
     * Creates parameters for updating a group
     */
    public static function forGroupUpdate(int $groupId, string $name, ?string $description = null): array
    {
        $params = [
            'group' => $groupId,
            'name' => $name,
        ];

        if ($description !== null) {
            $params['description'] = $description;
        }

        return $params;
    }

    /**
     * Creates parameters for creating a group with a complex form
     */
    public static function forGroupCreate(string $name, array $extra = []): array
    {
        return [
            'name' => $name,
            'form' => array_merge([
                'name' => $name,
                'personal_group' => $extra['personal_group'] ?? false,
                'description' => $extra['description'] ?? null,
            ], $extra),
        ];
    }

    /**
     * Creates parameters for updating a member
     */
    public static function forMemberUpdate(int $groupId, int $userId, string $role): array
    {
        return [
            'group' => $groupId,
            'user_id' => $userId,
            'role' => $role,
        ];
    }

    /**
     * Wrapper for array - ensures keys are strings
     */
    public static function wrap(array $params): array
    {
        $wrapped = [];
        
        foreach ($params as $key => $value) {
            if (is_int($key)) {
                // Presumption order: group, name, form, etc.
                $wrapped[self::keyForIndex($key)] = $value;
            } else {
                $wrapped[$key] = $value;
            }
        }
        
        return $wrapped;
    }

    protected static function keyForIndex(int $index): string
    {
        $keys = ['group', 'name', 'form', 'user_id', 'role', 'project_id', 'task_id'];
        return $keys[$index] ?? 'param_' . $index;
    }
}