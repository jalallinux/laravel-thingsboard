<?php

namespace JalalLinuX\Thingsboard\Entities;

use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Tntity;

/**
 * @property array $systemData
 * @property bool $monolith
 */
class AdminSystemInfo extends Tntity
{
    protected $fillable = [
        'systemData',
        'monolith',
    ];

    protected $casts = [
        'systemData' => 'array',
        'monolith' => 'boolean',
    ];

    public function entityType(): ?EnumEntityType
    {
        return null;
    }

    /**
     * Get main information about system.
     *
     *
     * @return self
     *
     * @author Sabiee
     *
     * @group SYS_ADMIN
     */
    public function getSystemInfo(): static
    {
        $systemInfo = $this->api()->get('admin/systemInfo')->json();

        return $this->fill($systemInfo);
    }
}
