<?php

namespace JalalLinuX\Thingsboard\Entities;

use Illuminate\Support\Str;
use JalalLinuX\Thingsboard\Casts\CastId;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Thingsboard;
use JalalLinuX\Thingsboard\Tntity;

/**
 * @property string $key
 * @property array $id
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
     * @param  string|null  $key
     * @param  array|null  $jsonValue
     * @param  string|null  $id
     * @return $this
     *
     * @author JalalLinuX
     *
     * @group SYS_ADMIN
     */
    public function saveAdminSettings(string $key = null, array $jsonValue = null, string $id = null): static
    {
        $id = $id ?? $this->id['id'] ?? Str::uuid()->toString();

        Thingsboard::validation(! Str::isUuid($id), 'uuid', ['attribute' => 'settingId']);

        $payload = array_merge($this->attributesToArray(), [
            'key' => $key ?? $this->forceAttribute('key'),
            'jsonValue' => $jsonValue ?? $this->forceAttribute('jsonValue'),
        ]);

        $adminSettings = $this->api()->post('admin/settings', $payload)->json();

        return $this->fill($adminSettings);
    }

    /**
     * Attempts to send test email to the System Administrator User using Mail Settings provided as a parameter.
     * You may change the 'To' email in the user profile of the System Administrator.
     *
     *
     * @param  array  $jsonValue
     * @return bool
     *
     * @author JalalLinuX
     *
     * @group SYS_ADMIN
     */
    public function sendTestMail(array $jsonValue): bool
    {
        $mailSetting = $this->getAdminSettings('mail');
        $mailSetting->jsonValue = array_merge($mailSetting->jsonValue, $jsonValue);

        return $this->api()->post('admin/settings/testMail', $mailSetting->attributesToArray())->successful();
    }
}
