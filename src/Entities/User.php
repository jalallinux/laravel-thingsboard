<?php

namespace JalalLinuX\Thingsboard\Entities;

use Illuminate\Support\Str;
use JalalLinuX\Thingsboard\Casts\Id;
use JalalLinuX\Thingsboard\Enums\ThingsboardAuthority;
use JalalLinuX\Thingsboard\Enums\ThingsboardEntityType;
use JalalLinuX\Thingsboard\Enums\UserSortProperty;
use JalalLinuX\Thingsboard\ThingsboardId;
use JalalLinuX\Thingsboard\ThingsboardPaginatedResponse;
use JalalLinuX\Thingsboard\ThingsboardPaginationArguments;
use JalalLinuX\Thingsboard\Tntity;

/**
 * @property ThingsboardId $id
 * @property \DateTime $createdTime
 * @property ThingsboardId $tenantId
 * @property ThingsboardId $customerId
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
        'id' => Id::class,
        'createdTime' => 'timestamp',
        'tenantId' => Id::class,
        'customerId' => Id::class,
        'additionalInfo' => 'array',
        'authority' => ThingsboardAuthority::class,
    ];

    public function entityType(): ?ThingsboardEntityType
    {
        return ThingsboardEntityType::USER();
    }

    /**
     * Get Users
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN | CUSTOMER_USER
     */
    public function getUsers(ThingsboardPaginationArguments $paginationArguments): ThingsboardPaginatedResponse
    {
        $response = $this->api()->get('users', $paginationArguments->queryParams());

        return $this->paginatedResponse($response, $paginationArguments);
    }

    /**
     * Get Customer Users
     *
     * @throws \Throwable
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN
     */
    public function getCustomerUsers(ThingsboardPaginationArguments $paginationArguments, string $customerId = null): ThingsboardPaginatedResponse
    {
        $customerId = $customerId ?? $this->forceAttribute('customerId')->id;

        throw_if(
            ! Str::isUuid($customerId),
            $this->exception('method "customerId" argument must be a valid uuid.'),
        );

        $paginationArguments->validateSortProperty(UserSortProperty::class);

        $response = $this->api()->get("customer/{$customerId}/users", $paginationArguments->queryParams());

        return $this->paginatedResponse($response, $paginationArguments);
    }

    /**
     * Get Tenant Users
     *
     * @throws \Throwable
     *
     * @author JalalLinuX
     *
     * @group SYS_ADMIN
     */
    public function getTenantAdmins(ThingsboardPaginationArguments $paginationArguments, string $tenantId = null): ThingsboardPaginatedResponse
    {
        $tenantId = $tenantId ?? $this->forceAttribute('tenantId')->id;

        throw_if(
            ! Str::isUuid($tenantId),
            $this->exception('method "tenantId" argument must be a valid uuid.'),
        );

        $paginationArguments->validateSortProperty(UserSortProperty::class);

        $response = $this->api()->get("tenant/{$tenantId}/users", $paginationArguments->queryParams());

        return $this->paginatedResponse($response, $paginationArguments);
    }

    /**
     * Create or update the User.
     *
     * @author JalalLinuX
     *
     * @group SYS_ADMIN | TENANT_ADMIN
     */
    public function saveUser(bool $sendActivationMail = false): self
    {
        $payload = array_merge($this->getAttributes(), [
            'email' => $this->forceAttribute('email'),
            'authority' => $this->forceAttribute('authority'),
        ]);

        $user = $this->api()->post('user?sendActivationMail='.($sendActivationMail ? 'true' : 'false'), $payload)->json();

        return tap($this, fn () => $this->fill($user));
    }

    /**
     * Delete User
     *
     * @throws \Throwable
     *
     * @author JalalLinuX
     *
     * @group SYS_ADMIN | TENANT_ADMIN
     */
    public function deleteUser(string $id = null): bool
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        throw_if(
            ! Str::isUuid($id),
            $this->exception('method argument must be a valid uuid.'),
        );

        return $this->api()->delete("user/{$id}")->successful();
    }

    /**
     * Get User
     *
     * @throws \Throwable
     *
     * @author JalalLinuX
     *
     * @group
     */
    public function getUserById(string $id = null): self
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        throw_if(
            ! Str::isUuid($id),
            $this->exception('method argument must be a valid uuid.'),
        );

        $user = $this->api()->get("user/{$id}")->json();

        return tap($this, fn () => $this->fill($user));
    }

    /**
     * Get the activation link for the user.
     * The base url for activation link is configurable in the general settings of system administrator.
     * @param string|null $id
     * @return string
     * @throws \Throwable
     * @author JalalLinuX
     * @group SYS_ADMIN | TENANT_ADMIN
     */
    public function getActivationLink(string $id = null): string
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        throw_if(
            ! Str::isUuid($id),
            $this->exception('method argument must be a valid uuid.'),
        );

        return $this->api()->get("user/{$id}/activationLink")->body();
    }
}
