<?php

namespace JalalLinuX\Thingsboard\Entities;

use Illuminate\Support\HigherOrderTapProxy;
use Illuminate\Support\Str;
use JalalLinuX\Thingsboard\Casts\CastId;
use JalalLinuX\Thingsboard\Enums\EnumAdminSettingsKey;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Tntity;

class AdminSettings extends Tntity
{

    protected $fillable = [
        'key',
        'id',
        'tenantId',
        'jsonValue',
    ];

    protected $casts = [
        'id' => 'array',
        'tenantId' => CastId::class,
        'jsonValue' => 'array',
    ];


    public function entityType(): ?EnumEntityType
    {
        return null;
    }

    /**
     * Creates or Updates the Administration Settings.
     * Platform generates random Administration Settings Id during settings creation.
     * The Administration Settings Id will be present in the response.
     * Specify the Administration Settings Id when you would like to update the Administration Settings.
     * Referencing non-existing Administration Settings Id will cause an error.
     *
     *
     * @param EnumAdminSettingsKey $key
     * @return self
     * @author Sabiee
     *
     * @group SYS_ADMIN
     */
    public function getAdminSettings(EnumAdminSettingsKey $key): static
    {
        $adminSettings = $this->api()->get("admin/settings/{$key}")->json();
        return tap($this, fn() => $this->fill($adminSettings));
    }
}
