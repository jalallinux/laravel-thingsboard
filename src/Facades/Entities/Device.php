<?php

namespace JalalLinuX\Tntity\Facades\Entities;

use JalalLinuX\Tntity\Facades\EntityFacade;

/**
 * @see \JalalLinuX\Tntity\Entities\Device\Device
 *
 * @property array $id
 * @property \DateTime $createdTime
 * @property string $type
 * @property string $name
 * @property string $label
 * @property array $additionalInfo
 * @property array $customerId
 * @property array $deviceProfileId
 * @property array $deviceData
 * @property string $searchText
 * @property array $tenantId
 * @property array $firmwareId
 * @property array $softwareId
 * @property array $externalId
 *
 * @method $this getById(string $id = null)
 */
class Device extends EntityFacade
{
}
