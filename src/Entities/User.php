<?php

namespace JalalLinuX\Thingsboard\Entities;

use JalalLinuX\Thingsboard\ThingsboardPaginatedResponse;
use JalalLinuX\Thingsboard\ThingsboardPaginationArguments;
use JalalLinuX\Thingsboard\Tntity;

/**
 * @property array $id
 * @property \DateTime $createdTime
 * @property array $tenantId
 * @property array $customerId
 * @property string $email
 * @property string $name
 * @property string $authority
 * @property string $lastName
 * @property string $firstName
 * @property array $additionalInfo
 * @property array $phone
 */
class User extends Tntity
{
    protected $fillable = [
        'id',
        'createdTime',
        'tenantId',
        'customerId',
        'email',
        'name',
        'authority',
        'lastName',
        'firstName',
        'additionalInfo',
        'phone',
    ];

    protected $casts = [
        'id' => 'array',
        'createdTime' => 'timestamp',
        'tenantId' => 'array',
        'customerId' => 'array',
        'additionalInfo' => 'array',
    ];

    /**
     * Get Users
     * @param ThingsboardPaginationArguments $paginationArguments
     * @return ThingsboardPaginatedResponse
     * @author JalalLinuX
     * @group TENANT_ADMIN | CUSTOMER_USER
     */
    public function getUsers(ThingsboardPaginationArguments $paginationArguments): ThingsboardPaginatedResponse
    {
        $response = $this->api(true)->get('users', $paginationArguments->queryParams());

        return $this->paginatedResponse($response, $paginationArguments);
    }
}
