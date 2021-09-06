<?PHP

namespace IAM;

use stdClass;

use Knight\armor\Curl;
use Knight\armor\Output;
use Knight\armor\Request as KRequest;
use Knight\armor\Navigator;

use IAM\Sso;
use IAM\Configuration;

class Request
{
    const HTTP_LOGIN = 'login';

    const HEADER_OVERRIDE = 'x-override-ip';
    const HEADER_APPLICATION = 'x-application';
    const HEADER_AUTHOTIZATION = 'x-authorization';
    const HEADER_OVERLOAD = 'x-overload';

    const SKIPSTATUS = 0x1; // (bool)

    protected static $curl;     // (curl)
    protected static $token;    // (string)
    protected static $overload; // (array)

    final protected function __construct()
    {
        $curl = new Curl();
        static::setCURL($curl);
    }

    final public static function instance(string $token = null, string $email = null, string $password = null) : self
    {
        $arguments = get_defined_vars();
        $arguments = array_filter($arguments, function (?string $item) {
			return false === is_null($item) && strlen($item);
		});

        static $instance;
        if (empty($arguments)
            && null !== $instance) return $instance;

        $instance = new static();
        $cookie_name = Configuration::getCookieName();
        $header_authorization = KRequest::header(static::HEADER_AUTHOTIZATION);
        if (empty($arguments)
            && !array_key_exists($cookie_name, $_COOKIE)
            && null === $header_authorization) static::login();

        $instance_authorization = $token ?? $header_authorization ?? $_COOKIE[$cookie_name] ?? null;
        if (2 > count($arguments)
            && is_string($instance_authorization)) $instance::setToken($instance_authorization);

        static::prepare();

        if (2 === count($arguments)) {
            $get = Configuration::getHost() . Sso::PATH_API_LOGIN;
            $response = $instance::callAPI($get, $arguments);
            $instance::setToken($response->authorization);
            static::prepare();
        }

        return $instance;
    }

    public static function setOverload(string ...$policies) : void
	{
		static::$overload = $policies;
        static::prepare();
	}

	public static function getOverload() :? array
	{
		return static::$overload;
	}

    public static function callAPI(string $get, ?array $post = [], int $flags = 0) : stdClass
    {
        static::instance();

        $curl = static::getCURL();
        $curl_response = $curl->request($get, $post);
        $curl_response_status = !property_exists($curl_response, 'status') || false === $curl_response->status;
        if (false === $curl_response_status
            || true === (bool)(static::SKIPSTATUS & $flags)) return $curl_response;

        echo Output::json($curl_response);
        exit;
    }

    public static function getCURL() :? Curl
    {
        return static::$curl;
    }

    public static function getToken() :? string
    {
        return static::$token;
    }

    protected static function prepare() : void
    {
        $curl = static::getCURL();
        if (null !== $curl) $curl->setHeader(...static::getHeader());
    }

    protected static function login() : void
    {
        Navigator::exception(function (string $current) {
            $redirect = base64_encode($current);
            $redirect = urlencode($redirect);
            $redirect = Configuration::getHost() . chr(63) . static::HTTP_LOGIN . chr(61) . $redirect;
            return $redirect;
        });
    }

    protected static function setCURL(Curl $curl) : void
    {
        static::$curl = $curl;
    }

    protected static function setToken(string $token) : void
    {
        static::$token = $token;
    }

    protected static function getHeader() : array
    {
        $authorization = [
            static::HEADER_OVERRIDE . chr(58) . chr(32) . Navigator::getClientIP(Navigator::HTTP_X_OVERRIDE_IP_ENABLE),
            static::HEADER_APPLICATION . chr(58) . chr(32) . Configuration::getApplicationKey()
        ];
        $authorization_token = static::getToken();
        if (null !== $authorization_token) array_push($authorization, static::HEADER_AUTHOTIZATION . chr(58) . chr(32) . $authorization_token);

        $overload = static::getOverload();
        if (null !== $overload) {
            $overload_encrypt = Output::json($overload);
            $overload_encrypt = Sso::getCipher()->encrypt($overload_encrypt);
            array_push($authorization, static::HEADER_OVERLOAD . chr(58) . chr(32) . $overload_encrypt);
        }

        return $authorization;
    }
}