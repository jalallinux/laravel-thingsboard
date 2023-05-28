<?php

namespace JalalLinuX\Thingsboard\Entities;

use Illuminate\Support\Str;
use JalalLinuX\Thingsboard\Casts\CastId;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Enums\EnumUserSortProperty;
use JalalLinuX\Thingsboard\infrastructure\Id;
use JalalLinuX\Thingsboard\infrastructure\PaginatedResponse;
use JalalLinuX\Thingsboard\infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tntity;

/**
 * @property Id $id
 * @property \DateTime $createdTime
 * @property Id $tenantId
 * @property Id $customerId
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
        'id' => CastId::class,
        'createdTime' => 'timestamp',
        'tenantId' => CastId::class,
        'customerId' => CastId::class,
        'additionalInfo' => 'array',
        'authority' => EnumAuthority::class,
    ];

    public function entityType(): ?EnumEntityType
    {
        return EnumEntityType::USER();
    }

    /**
     * Returns a page of users owned by tenant or customer.
     * The scope depends on authority of the user that performs the request.
     * You can specify parameters to filter the results.
     * The result is wrapped with PageData object that allows you to iterate over result set using pagination.
     * See the 'Model' tab of the Response Class for more details.
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN | CUSTOMER_USER
     */
    public function getUsers(PaginationArguments $paginationArguments): PaginatedResponse
    {
        $response = $this->api()->get('users', $paginationArguments->queryParams());

        return $this->paginatedResponse($response, $paginationArguments);
    }

    /**
     * Returns a page of users owned by customer.
     * You can specify parameters to filter the results.
     * The result is wrapped with PageData object that allows you to iterate over result set using pagination.
     * See the 'Model' tab of the Response Class for more details.
     *
     * @throws \Throwable
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN
     */
    public function getCustomerUsers(PaginationArguments $paginationArguments, string $customerId = null): PaginatedResponse
    {
        $customerId = $customerId ?? $this->forceAttribute('customerId')->id;

        throw_if(
            ! Str::isUuid($customerId),
            $this->exception('method "customerId" argument must be a valid uuid.'),
        );

        $paginationArguments->validateSortProperty(EnumUserSortProperty::class);

        $response = $this->api()->get("customer/{$customerId}/users", $paginationArguments->queryParams());

        return $this->paginatedResponse($response, $paginationArguments);
    }

    /**
     * Returns a page of users owned by tenant.
     * You can specify parameters to filter the results.
     * The result is wrapped with PageData object that allows you to iterate over result set using pagination.
     * See the 'Model' tab of the Response Class for more details.
     *
     * @throws \Throwable
     *
     * @author JalalLinuX
     *
     * @group SYS_ADMIN
     */
    public function getTenantAdmins(PaginationArguments $paginationArguments, string $tenantId = null): PaginatedResponse
    {
        $tenantId = $tenantId ?? $this->forceAttribute('tenantId')->id;

        throw_if(
            ! Str::isUuid($tenantId),
            $this->exception('method "tenantId" argument must be a valid uuid.'),
        );

        $paginationArguments->validateSortProperty(EnumUserSortProperty::class);

        $response = $this->api()->get("tenant/{$tenantId}/users", $paginationArguments->queryParams());

        return $this->paginatedResponse($response, $paginationArguments);
    }

    /**
     * Create or update the User.
     * When creating user, platform generates User ID as time-based UUID.
     * The newly created User ID will be present in the response.
     * Specify existing User ID to update the user.
     * Referencing non-existing User ID will cause 'Not Found' error.
     * User email is unique for entire platform setup.
     * Remove 'id', 'tenantId' and optionally 'customerId' from the request body example (below) to create new User entity.
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
     * Deletes the User, it's credentials and all the relations (from and to the User).
     * Referencing non-existing User ID will cause an error.
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

        return $this->api(handleException: self::config('rest.exception.throw_bool_methods'))->delete("user/{$id}")->successful();
    }

    /**
     * Fetch the User object based on the provided User ID.
     * If the user has the authority of 'SYS_ADMIN', the server does not perform additional checks.
     * If the user has the authority of 'TENANT_ADMIN', the server checks that the requested user is owned by the same tenant.
     * If the user has the authority of 'CUSTOMER_USER', the server checks that the requested user is owned by the same customer.
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
     *
     * @throws \Throwable
     *
     * @author JalalLinuX
     *
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
