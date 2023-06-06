<?php

namespace JalalLinuX\Thingsboard\Entities;

use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Tntity;

/**
 * @property boolean $updateAvailable
 * @property string $currentVersion
 * @property string $latestVersion
 * @property string $upgradeInstructionsUrl
 * @property string $currentVersionReleaseNotesUrl
 * @property string $latestVersionReleaseNotesUrl
 */
class AdminUpdates extends Tntity
{
    protected $fillable = [
        'updateAvailable',
        'currentVersion',
        'latestVersion',
        'upgradeInstructionsUrl',
        'currentVersionReleaseNotesUrl',
        'latestVersionReleaseNotesUrl',
    ];

    protected $casts = [
        'updateAvailable' => 'boolean',
    ];

    public function entityType(): ?EnumEntityType
    {
        return null;
    }

    /**
     * Check notifications about new platform releases.
     *
     *
     * @return self
     *
     * @author Sabiee
     *
     * @group SYS_ADMIN
     */
    public function checkUpdates(): static
    {
        $systemInfo = $this->api()->get('admin/updates')->json();

        return $this->fill($systemInfo);
    }
}
