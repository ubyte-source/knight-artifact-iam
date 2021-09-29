<?PHP

namespace IAM;

use stdClass;

use IAM\Request;
use IAM\Configuration;

class Gateway
{
    const PATH_API_LINK = 'api/sso/application/link';

    final protected function __construct() {}

    public static function getLink(string $basename) :? string
    {
        $request = Configuration::getHost();
        $request_response = Request::callAPI($request . static::PATH_API_LINK . chr(47) . $basename);
        if (property_exists($request_response, 'data')) return $request_response->data;
        return null;
    }

    public static function getStructure(string $basename, string $path) :? stdClass
    {
        $request = static::getLink($basename);
        $request_response = Request::callAPI($request . 'structure' . chr(47) . $path);
        if (property_exists($request_response, 'data')) return $request_response->data;
        return null;
    }

    public static function callAPI(string $basename, string $path, array $post = []) : stdClass
    {
        $request = static::getLink($basename);
        return Request::callAPI($request . 'api' . chr(47) . $path, $post);
    }
}