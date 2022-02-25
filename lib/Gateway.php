<?PHP

namespace IAM;

use stdClass;

use Knight\armor\Output;

use IAM\Request;
use IAM\Configuration;

/* This class is used to call the API of the microservice application */

class Gateway
{
    const PATH_API_LINK = 'api/sso/application/link';

    final protected function __construct() {}

    /**
     * Returns the link to the microservice application
     * 
     * @param string basename The name of the microservice application you want to get a link to.
     * 
     * @return The link to the microservice application.
     */

    public static function getLink(string $basename) :? string
    {
        $request = Configuration::getHost();
        $request_response = Request::callAPI($request . static::PATH_API_LINK . chr(47) . $basename);
        if (property_exists($request_response, Output::APIDATA)) return $request_response->{Output::APIDATA};
        return null;
    }

    /**
     * Get the structure of a remote database table
     * 
     * @param string basename The name of the remote database table you want to get the structure of.
     * @param string path The path to the remote database table.
     * 
     * @return The structure of the remote database table.
     */

    public static function getStructure(string $basename, string $path) :? stdClass
    {
        $request = static::getLink($basename);
        $request_response = Request::callAPI($request . 'structure' . chr(47) . $path);
        if (property_exists($request_response, Output::APIDATA)) return $request_response->{Output::APIDATA};
        return null;
    }

    /**
     * This function calls the API and returns the response as a stdClass object
     * 
     * @param string basename The name of the API.
     * @param string path The path to the API endpoint.
     * @param array post an array of parameters to pass to the API call.
     * 
     * @return A stdClass object.
     */

    public static function callAPI(string $basename, string $path, array $post = []) : stdClass
    {
        $request = static::getLink($basename);
        return Request::callAPI($request . 'api' . chr(47) . $path, $post);
    }
}