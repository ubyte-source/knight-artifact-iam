<?PHP

namespace IAM;

use Knight\Configuration as KnightConfiguration;

use Knight\armor\Navigator;

/* The Configuration class is used to read the configuration file and return the configuration values */

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

    /**
     * Get the application basename from the configuration file
     * 
     * @return The basename of the application.
     */

    public static function getApplicationBasename() : string
    {
        return static::getConfiguration(static::CONFIGURATION_APPLICATION_BASENAME, true, static::CONFIGURATION_FILENAME);
    }

    /**
     * Get the application key from the configuration file
     * 
     * @return The application key.
     */

    public static function getApplicationKey() : string
    {
        return static::getConfiguration(static::CONFIGURATION_APPLICATION_KEY, true, static::CONFIGURATION_FILENAME);
    }

    /**
     * Returns the policy separator for the current configuration
     * 
     * @return The policy separator is a string that is used to separate the policy name from the policy parameters.
     */

    public static function getPolicySeparator() : string
    {
        return static::getConfiguration(static::CONFIGURATION_POLICY_SEPARATOR, true, static::CONFIGURATION_FILENAME);
    }

    /**
     * Get the host name from the configuration file
     * 
     * @return The host name.
     */

    public static function getHost() : string
    {
        return static::getConfiguration(static::CONFIGURATION_HOST_IAM, true, static::CONFIGURATION_FILENAME);
    }

    /**
     * Get the cookie name from the configuration file
     * 
     * @return The cookie name.
     */

    public static function getCookieName() : string
    {
        return static::getConfiguration(static::CONFIGURATION_COOKIE_NAME, true, static::CONFIGURATION_FILENAME);
    }
}