<?PHP

namespace IAM;

use Knight\Configuration as KnightConfiguration;

use Knight\armor\Navigator;

class Configuration
{
    use KnightConfiguration;

    const CONFIGURATION_FILENAME = 'IAM';
    const CONFIGURATION_POLICY_SEPARATOR = 0x69780;
    const CONFIGURATION_APPLICATION_BASENAME = 0x2774;
    const CONFIGURATION_APPLICATION_KEY = 0x2775;
    const CONFIGURATION_COOKIE_NAME = 0x2904;
    const CONFIGURATION_HOST_IAM = 0x283c;

    final protected function __construct() {}

    public static function getApplicationBasename() : string
    {
        return static::getConfiguration(static::CONFIGURATION_APPLICATION_BASENAME, true, static::CONFIGURATION_FILENAME);
    }

    public static function getApplicationKey() : string
    {
        return static::getConfiguration(static::CONFIGURATION_APPLICATION_KEY, true, static::CONFIGURATION_FILENAME);
    }

    public static function getPolicySeparator() : string
    {
        return static::getConfiguration(static::CONFIGURATION_POLICY_SEPARATOR, true, static::CONFIGURATION_FILENAME);
    }

    public static function getHost() : string
    {
        return static::getConfiguration(static::CONFIGURATION_HOST_IAM, true, static::CONFIGURATION_FILENAME);
    }

    public static function getCookieName() : string
    {
        return static::getConfiguration(static::CONFIGURATION_COOKIE_NAME, true, static::CONFIGURATION_FILENAME);
    }
}