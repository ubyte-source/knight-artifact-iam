<?PHP

namespace IAM;

use stdClass;

use Knight\armor\Cipher;
use Knight\armor\Output;
use Knight\armor\Cookie;
use Knight\armor\Request;
use Knight\armor\Language;
use Knight\armor\Navigator;

use IAM\Request as IAMRequest;
use IAM\Configuration;

class Sso
{
    const PATH_API_LOGIN = 'api/iam/user/login';
    const PATH_API_POLICY_MANDATORIES = 'api/iam/policy/mandatories';
    const PATH_API_POLICY = 'api/iam/user/rules';
    const PATH_API_WHOAMI = 'api/iam/user/whoami';

    const PATH_API_USER_READ = 'api/iam/user/read';
    const PATH_API_USER_HIERARCHY = 'api/iam/user/hierarchy';
    const PATH_API_USER_ESCALATION = 'api/iam/user/escalation';

    const PATH_API_APPLICATION_READ = 'api/sso/application/read';

    const AUTHORIZATION = 'authorization';
    const USER_LANGUAGE = 'language';
    const IDENTITY = '_key';

    const MATCH = '/^(%s)$/';

    protected static $whoami;    // (object)
    protected static $rules;     // (array)

    final protected function __construct() {}

    public static function getCipher() : Cipher
    {
        $cipher = new Cipher();
        $cipher->setKeyPersonal((string)Navigator::getClientIP(Navigator::HTTP_X_OVERRIDE_IP_ENABLE));
        return $cipher;
    }

    public static function auth() : void
    {
        $cookie_content = Navigator::getUrlWithQueryString();
        $cookie_content = parse_url($cookie_content, PHP_URL_PATH);
        $cookie_content = basename($cookie_content);
        $cookie_content = base64_decode($cookie_content);

        $check = IAMRequest::instance($cookie_content);
        $check = static::getWhoami();
        if (null === $check
            || true !== Cookie::set(Configuration::getCookieName(), $cookie_content)) Navigator::exception();

        $return_url = $_SERVER[Navigator::HTTP_ORIGIN] ?? Navigator::getUrl();
        $return_url_get = Request::get(Navigator::RETURN_URL);
        if (null !== $return_url_get) $return_url = base64_decode($return_url_get);

        Navigator::noCache();
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: ' . $return_url);

        exit;
    }

    public static function getWhoami() :? stdClass
    {
        IAMRequest::instance();
        if (null === static::$whoami) static::setWhoami();
        return static::$whoami;
    }

    public static function getWhoamiKey() :? string
    {
        $whoami = static::getWhoami();
        if (property_exists($whoami, $key = static::IDENTITY)) return $whoami->$key;
        return null;
    }

    public static function getWhoamiLanguage() :? string
    {
        $whoami = static::getWhoami();
        if (property_exists($whoami, $key = static::USER_LANGUAGE)) return $whoami->$key;
        return null;
    }

    public static function requestMandatoryPolicies(string ...$filters) : bool
    {
        if (empty($filters)) Output::print(false);

        $request = Configuration::getHost();
        $request_response = IAMRequest::callAPI($request . static::PATH_API_POLICY_MANDATORIES, $filters, IAMRequest::SKIPSTATUS);
        if (property_exists($request_response, 'status')) return $request_response->status;
        return false;
    }

    public static function getPolicies(string ...$filters) : array
    {
        IAMRequest::instance();

        static::initializeAllAvalilableRules();

        $rules = static::$rules;
        if (!!$overload = IAMRequest::getOverload()) $rules = array_merge($rules, $overload);

        $filters = array_diff($filters, array('%'));
        if (empty($filters)) return $rules;

        $find = array('%', '/');
		$replace = array('.*', '\/');
		$filters_regex_rule = array_map(function ($item) use ($find, $replace) {
			return str_replace($find, $replace, $item);
        }, $filters);

		$filters_regex_rule = implode('|', $filters_regex_rule);
        $filters_regex_rule = sprintf(static::MATCH, $filters_regex_rule);
        $filters = array_filter($rules, function ($rule) use ($filters_regex_rule) {
            return preg_match($filters_regex_rule, $rule);
        });

        $filters = array_values($filters);
        return $filters;
    }

    public static function youHaveNoPolicies(string ...$policies_mandatory) : bool
    {
        $find = array('%', '/');
		$replace = array('(.*)', '\/');
        $policies = static::getPolicies(...$policies_mandatory);
		$policies_filter_regex = array_map(function ($item) use ($find, $replace) {
			return str_replace($find, $replace, $item);
        }, $policies_mandatory);
        
        $result = count($policies_filter_regex);
		foreach ($policies_filter_regex as $filter) {
            $regex_match = sprintf(static::MATCH, $filter);
			foreach ($policies as $rule) {
                if (!preg_match($regex_match, $rule)) continue;
                $result--;
                continue 2;
			}
        }
		return $result !== 0;
    }

    public static function getUsers(?string $get = '', ?array $post = null, string ...$keys) :? array
    {
        $request_post = $post ?? [];
        $request_post[static::IDENTITY] = $keys;

        $request = Configuration::getHost();
        $request_response = IAMRequest::callAPI($request . static::PATH_API_USER_READ . chr(63) . $get, $request_post);
        if (!property_exists($request_response, Output::APIDATA)) return null;

        $request_response_key = array_column($request_response->{Output::APIDATA}, static::IDENTITY);
        return array_combine($request_response_key, $request_response->{Output::APIDATA});
    }

    public static function getHierarchy(string $type = '_key') :? array
    {
        $request = Configuration::getHost();
        $request_response = IAMRequest::callAPI($request . static::PATH_API_USER_HIERARCHY . chr(47) . $type);
        if (property_exists($request_response, Output::APIDATA)) return $request_response->{Output::APIDATA};
        return null;
    }

    public static function getEscalation(string $route, string $skip = 'none') :? array
    {
        IAMRequest::instance();

        $request = Configuration::getHost();
        $request_response = IAMRequest::callAPI($request . static::PATH_API_USER_ESCALATION . chr(47) . $route . chr(63) . 'skip' . chr(61) . $skip, null, IAMRequest::SKIPSTATUS);
        if (property_exists($request_response, Output::APIDATA)) return $request_response->{Output::APIDATA};
        return null;
    }

    public static function getApplications(?string $get = '', ?array $post = null, string ...$keys) :? array
    {
        $request_post = $post ?? [];
        $request_post[static::IDENTITY] = $keys;

        $request = Configuration::getHost();
        $request_response = IAMRequest::callAPI($request . static::PATH_API_APPLICATION_READ . chr(63) . $get, $request_post);
        if (!property_exists($request_response, Output::APIDATA)) return null;

        $request_response_key = array_column($request_response->{Output::APIDATA}, static::IDENTITY);
        $request_response_key = array_combine($request_response_key, $request_response->{Output::APIDATA});

        return $request_response_key;
    }

    protected static function setWhoami() : void
    {
        $request = Configuration::getHost();
        $request_response = IAMRequest::callAPI($request . static::PATH_API_WHOAMI, null, IAMRequest::SKIPSTATUS);
        if (!property_exists($request_response, Output::APIDATA)) Navigator::exception(function () {
            static::logout();
        });

        static::$whoami = $request_response->{Output::APIDATA};

        if (null !== static::getWhoamiLanguage()) Language::setSpeech(static::getWhoamiLanguage());
    }

    protected static function initializeAllAvalilableRules() : void
    {
        if (static::$rules !== null) return;

        $request = Configuration::getHost() . static::PATH_API_POLICY;
        $request_response = IAMRequest::callAPI($request, null, IAMRequest::SKIPSTATUS);
        if (false === property_exists($request_response, Output::APIDATA)) Navigator::exception(function () {
            static::logout();
        });

        static::$rules = $request_response->{Output::APIDATA};

        if (!empty(static::$rules)) static::overload();
    }

    protected static function logout() : void
    {
        Cookie::set(Configuration::getCookieName(), null, -1);
    }

    protected static function overload() : void
    {
        $header = Request::header(IAMRequest::HEADER_OVERLOAD);
        $header_decrypt = static::getCipher()->decrypt($header);
        if (null !== $header_decrypt) {
            $header_decrypt = Request::JSONDecode($header_decrypt);
            $header_decrypt = array_values($header_decrypt);
            IAMRequest::setOverload(...$header_decrypt);
        }
    }
}