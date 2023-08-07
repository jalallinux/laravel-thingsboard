<?php

namespace JalalLinuX\Thingsboard\Entities;

use JalalLinuX\Thingsboard\Casts\CastId;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Tntity;

/**
 * @property string $key
 * @property Id $id
 * @property Id $tenantId
 * @property array $jsonValue
 */
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
     * @param  string  $key
     * @return self
     *
     * @author Sabiee
     *
     * @group SYS_ADMIN
     */
    public function getAdminSettings(string $key): static
    {
        $adminSettings = $this->api()->get("admin/settings/{$key}")->json();

        return $this->fill($adminSettings);
    }

    /**
     * Creates or Updates the Administration Settings.
     * Platform generates random Administration Settings Id during settings creation.
     * The Administration Settings Id will be present in the response.
     * Specify the Administration Settings Id when you would like to update the Administration Settings.
     * Referencing non-existing Administration Settings Id will cause an error.
     *
     * @param  string  $key
     * @param  array  $values
     * @return $this
     *
     * @author JalalLinuX
     *
     * @group SYS_ADMIN
     */
    public function saveAdminSettings(string $key, array $values): static
    {
        $payload = array_merge($this->attributesToArray(), [
            'key' => $key,
            'jsonValue' => $values,
        ]);

        $adminSettings = $this->api()->post('admin/settings', $payload)->json();

        return $this->fill($adminSettings);
    }
}
