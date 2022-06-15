# Documentation knight-artifact-iam

Knight PHP library for [IAM](https://github.com/energia-source/energia-europa-iam) integration.

**NOTE:** This repository is part of [Knight](https://github.com/energia-source/knight). Any
support requests, bug reports, or development contributions should be directed to
that project.

## Structure

library:
- [IAM](https://github.com/energia-source/knight-artifact-iam/tree/main/lib)

<br>

#### ***Class IAM\Sso usable methods***

##### `public static function getCipher() : Cipher`

The function returns a new instance of the Cipher class with the key set to the IP address of the client

 * **Returns:** The cipher object.

##### `public static function auth() : void`

* Get the cookie content from the URL.
* Decode the cookie content.
* Check if the decoded content is a valid IAM token.
* Set the cookie content to the decoded content.
* Redirect to the return URL

##### `public static function getWhoami() :? stdClass`

It returns the current user's identity.

 * **Returns:** An object with the following properties:

##### `public static function getWhoamiKey() :? string`

Get the key of the identity property of the current whoami object

 * **Returns:** The value of the `IDENTITY` property of the `Whoami` class.

##### `public static function getWhoamiLanguage() :? string`

Returns the language of the current user

 * **Returns:** The language code of the user.

##### `public static function requestMandatoryPolicies(string ...$filters) : bool`

* Request the list of mandatory policies

 * **Returns:** The status of the request.

##### `public static function getPolicies(string ...$filters) : array`

Returns an array of all the policies that match the given filters

 * **Returns:** An array of policy names that match the filters.

##### `public static function youHaveNoPolicies(string ...$policies_mandatory) : bool`

Check if the user has the policies listed in the input array

 * **Returns:** A boolean value indicating success or failure.

##### `public static function getUsers(?string $get = '', ?array $post = null, string ...$keys) :? array`

Get users from the API

 * **Parameters:**
   * `get` — GET parameter to append to the URL.
   * `post` — post data to send to the API.

     <p>
 * **Returns:** An array of users.

##### `public static function getHierarchy(string $type = '_key') :? array`

Get the hierarchy of the user

 * **Parameters:** `string` — _key, firstname, email, ..ecc

     <p>
 * **Returns:** The response is an array of the users in the hierarchy.

##### `public static function getEscalation(string $route, string $skip = 'none') :? array`

Get the escalation for a given route

 * **Parameters:**
   * `string` — The route to the escalation.
   * `string` — The type of escalation to skip.

     <p>
 * **Returns:** An array of escalation policies.

##### `public static function getApplications(?string $get = '', ?array $post = null, string ...$keys) :? array`

Get all applications

 * **Parameters:**
   * `get` — GET parameter to be used in the API call.
   * `post` — post data to send to the API.

     <p>
 * **Returns:** An array of application objects.

##### `public static function getRemoteOveloadPolicy() :? array`

This function decrypts the overload header and returns an array of the overload policy

 * **Returns:** An array of application objects.

<br>

#### ***Class IAM\Gateway usable methods***

##### `public static function getLink(string $basename) :? string`

Returns the link to the microservice application

 * **Parameters:** `string` — The name of the microservice application you want to get a link to.

     <p>
 * **Returns:** The link to the microservice application.

##### `public static function getStructure(string $basename, string $path) :? stdClass`

Get the structure of a remote database table

 * **Parameters:**
   * `string` — The name of the remote database table you want to get the structure of.
   * `string` — The path to the remote database table.

     <p>
 * **Returns:** The structure of the remote database table.

##### `public static function callAPI(string $basename, string $path, array $post = []) : stdClass`

This function calls the API and returns the response as a stdClass object

 * **Parameters:**
   * `string` — The name of the API.
   * `string` — The path to the API endpoint.
   * `array` — an array of parameters to pass to the API call.

     <p>
 * **Returns:** A stdClass object.

<br>

#### ***Class IAM\Request usable methods***

##### `public static function setOverload(string ...$policies) : void`

This function sets the overload policies for the current user

##### `public static function getOverload() :? array`

Return the policies overload array

 * **Returns:** `Nothing.` — 

##### `public static function callAPI(string $get, ?array $post = [], int $flags = 0) : stdClass`

This function will call the API and return the response

 * **Parameters:**
   * `string` — The URL to call.
   * `post` — post data to send to the API.
   * `int` — <p>
 * **Returns:** The response from the API call.

##### `public static function getCURL() :? Curl`

Returns the current instance of the Curl class

 * **Returns:** A Curl object.

##### `public static function getToken() :? string`

Get the token from the static property

 * **Returns:** The token to current authenticated user.

<br>

#### ***Class IAM\Configuration usable methods***

##### `public static function getApplicationBasename() : string`

Get the application basename from the configuration file

 * **Returns:** The basename of the application.

##### `public static function getApplicationKey() : string`

Get the application key from the configuration file

 * **Returns:** The application key.

##### `public static function getPolicySeparator() : string`

Returns the policy separator for the current configuration

 * **Returns:** The policy separator is a string that is used to separate the policy name from the policy parameters.

##### `public static function getHost() : string`

Get the host name from the configuration file

 * **Returns:** The host name.

##### `public static function getCookieName() : string`

Get the cookie name from the configuration file

 * **Returns:** The cookie name.

## Built With

* [PHP](https://www.php.net/) - PHP

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details
