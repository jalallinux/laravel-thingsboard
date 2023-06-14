<?php

namespace JalalLinuX\Thingsboard;

use DateTimeInterface;
use JalalLinuX\Thingsboard\Entities\Auth;
use JalalLinuX\Thingsboard\Entities\Mqtt;
use JalalLinuX\Thingsboard\Exceptions\Exception;
use JalalLinuX\Thingsboard\Infrastructure\CacheHandler;
use JalalLinuX\Thingsboard\Interfaces\ThingsboardUser;
use PhpMqtt\Client\ConnectionSettings;

/**
 * @method Entities\Auth auth(array $attributes = [])
 * @method Entities\User user(array $attributes = [])
 * @method Entities\Device device(array $attributes = [])
 * @method Entities\DeviceProfile deviceProfile(array $attributes = [])
 * @method Entities\Tenant tenant(array $attributes = [])
 * @method Entities\Customer customer(array $attributes = [])
 * @method Entities\DeviceApi deviceApi(array $attributes = [])
 * @method Entities\TenantProfile tenantProfile(array $attributes = [])
 * @method Entities\Usage usage(array $attributes = [])
 * @method Entities\Rpc rpc(array $attributes = [])
 * @method Entities\AuditLog auditLog(array $attributes = [])
 * @method Entities\AdminSettings adminSettings(array $attributes = [])
 * @method Entities\AdminSystemInfo adminSystemInfo(array $attributes = [])
 * @method Entities\AdminUpdates adminUpdates(array $attributes = [])
 * @method Entities\Telemetry telemetry(array $attributes = [])
 * @method Entities\WidgetBundle widgetBundle(array $attributes = [])
 * @method Entities\WidgetType widgetType(array $attributes = [])
 * @method Entities\Dashboard dashboard(array $attributes = [])
 * @method Entities\Asset asset(array $attributes = [])
 * @method Entities\AssetProfile assetProfile(array $attributes = [])
 * @method Entities\RuleChain ruleChain(array $attributes = [])
 * @method Entities\Event event(array $attributes = [])
 * @method Entities\EntityQuery entityQuery(array $attributes = [])
 * @method Entities\EntityRelation entityRelation(array $attributes = [])
 * @method Entities\Alarm alarm(array $attributes = [])
 * @method Entities\ComponentDescriptor componentDescriptor(array $attributes = [])
 */
class Thingsboard
{
    private ?ThingsboardUser $withUser;

    public function __construct(ThingsboardUser $withUser = null)
    {
        $this->withUser = $withUser;
    }

    public function __call(string $name, array $arguments)
    {
        $class = __NAMESPACE__.'\\Entities\\'.ucfirst($name);

        if (isset($this->withUser)) {
            return $class::instance(...$arguments)->withUser($this->withUser);
        }

        return $class::instance(...$arguments);
    }

    public function mqtt(string $accessToken): Mqtt
    {
        return new Mqtt($accessToken);
    }

    public static function cache(string $key, $value = null, DateTimeInterface $ttl = null)
    {
        $key = config('thingsboard.cache.prefix').$key;
        $cache = cache()->driver(config('thingsboard.cache.driver'));

        if (is_null($value)) {
            return $cache->get($key);
        }

        return $cache->put($key, $value, $ttl);
    }

    public static function fetchUserToken(ThingsboardUser $user, bool $flush = false)
    {
        $mail = $user->getThingsboardEmailAttribute();

        if ($flush) {
            return Auth::instance()->login($mail, $user->getThingsboardPasswordAttribute())->token;
        }

        if ($token = CacheHandler::get(CacheHandler::tokenCacheKey($mail))) {
            return $token;
        }

        return Auth::instance()->login($mail, $user->getThingsboardPasswordAttribute())->token;
    }

    public static function validation(bool $condition, string $messageKey, array $replaces = []): void
    {
        throw_if(
            $condition, new Exception(__("thingsboard::validation.{$messageKey}", $replaces))
        );
    }

    public static function exception(bool $condition, string $messageKey, array $replaces = [], int $code = 500): void
    {
        throw_if(
            $condition, new Exception(__("thingsboard::exception.{$messageKey}", $replaces), $code)
        );
    }

    public static function connectionSetting(string $username): ConnectionSettings
    {
        return (new ConnectionSettings)

            // The username used for authentication when connecting to the broker.
            ->setUsername($username)

            // The password used for authentication when connecting to the broker.
            ->setPassword(config('thingsboard.mqtt.connection_settings.auth.password'))

            // This flag determines if TLS should be used for the connection. The port which is used to
            // connect to the broker must support TLS connections.
            ->setUseTls(config('thingsboard.mqtt.connection_settings.tls.enabled'))

            // This flag determines if self signed certificates of the peer should be accepted.
            // Setting this to TRUE implies a security risk and should be avoided for production
            // scenarios and public services.
            ->setTlsSelfSignedAllowed(config('thingsboard.mqtt.connection_settings.tls.allow_self_signed_certificate'))

            // This flag determines if the peer certificate is verified, if TLS is used.
            ->setTlsVerifyPeer(config('thingsboard.mqtt.connection_settings.tls.verify_peer'))

            // This flag determines if the peer name is verified, if TLS is used.
            ->setTlsVerifyPeerName(config('thingsboard.mqtt.connection_settings.tls.verify_peer_name'))

            // The path to a Certificate Authority certificate which is used to verify the peer
            // certificate, if TLS is used.
            ->setTlsCertificateAuthorityFile(config('thingsboard.mqtt.connection_settings.tls.ca_file'))

            // The path to a directory containing Certificate Authority certificates which are
            // used to verify the peer certificate, if TLS is used.
            ->setTlsCertificateAuthorityPath(config('thingsboard.mqtt.connection_settings.tls.ca_path'))

            // The path to a client certificate file used for authentication, if TLS is used.
            //
            // The client certificate must be PEM encoded. It may optionally contain the
            // certificate chain of issuers.
            ->setTlsClientCertificateFile(config('thingsboard.mqtt.connection_settings.tls.client_certificate_file'))

            // The passphrase used to decrypt the private key of the client certificate,
            // which in return is used for authentication, if TLS is used.
            //
            // This option requires ConnectionSettings::setTlsClientCertificateFile() and
            // ConnectionSettings::setTlsClientCertificateKeyFile() to be used as well.
            ->setTlsClientCertificateKeyPassphrase(config('thingsboard.mqtt.connection_settings.tls.client_certificate_key_passphrase'))

            // If the broker should publish a last will message in the name of the client when the client
            // disconnects abruptly, this setting defines the topic on which the message will be published.
            //
            // A last will message will only be published if both this setting as well as the last will
            // message are configured.
            ->setLastWillTopic(config('thingsboard.mqtt.connection_settings.last_will.topic'))

            // If the broker should publish a last will message in the name of the client when the client
            // disconnects abruptly, this setting defines the message which will be published.
            //
            // A last will message will only be published if both this setting as well as the last will
            // topic are configured.
            ->setLastWillMessage(config('thingsboard.mqtt.connection_settings.last_will.message'))

            // The quality of service level the last will message of the client will be published with,
            // if it gets triggered.
            ->setLastWillQualityOfService(config('thingsboard.mqtt.connection_settings.last_will.quality_of_service'))

            // This flag determines if the last will message of the client will be retained, if it gets
            // triggered. Using this setting can be handy to signal that a client is offline by publishing
            // a retained offline state in the last will and an online state as first message on connect.
            ->setRetainLastWill(config('thingsboard.mqtt.connection_settings.last_will.retain'))

            // The connect timeout defines the maximum amount of seconds the client will try to establish
            // a socket connection with the broker. The value cannot be less than 1 second.
            ->setConnectTimeout(config('thingsboard.mqtt.connection_settings.connect_timeout'))

            // The socket timeout is the maximum amount of idle time in seconds for the socket connection.
            // If no data is read or sent for the given amount of seconds, the socket will be closed.
            // The value cannot be less than 1 second.
            ->setSocketTimeout(config('thingsboard.mqtt.connection_settings.socket_timeout'))

            // The resend timeout is the number of seconds the client will wait before sending a duplicate
            // of pending messages without acknowledgement. The value cannot be less than 1 second.
            ->setResendTimeout(config('thingsboard.mqtt.connection_settings.resend_timeout'))

            // This flag determines whether the client will try to reconnect automatically
            // if it notices a disconnect while sending data.
            // The setting cannot be used together with the clean session flag.
            ->setReconnectAutomatically(config('thingsboard.mqtt.connection_settings.auto_reconnect.enabled'))

            // Defines the maximum number of reconnect attempts until the client gives up.
            // This setting is only relevant if setReconnectAutomatically() is set to true.
            ->setMaxReconnectAttempts(config('thingsboard.mqtt.connection_settings.auto_reconnect.max_reconnect_attempts'))

            // Defines the delay between reconnect attempts in milliseconds.
            // This setting is only relevant if setReconnectAutomatically() is set to true.
            ->setDelayBetweenReconnectAttempts(config('thingsboard.mqtt.connection_settings.auto_reconnect.delay_between_reconnect_attempts'));

    }
}
