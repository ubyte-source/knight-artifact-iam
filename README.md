# Documentation knight-artifact-iam

Knight PHP library for [IAM](https://github.com/energia-source/energia-europa-iam) integration.

**NOTE:** This repository is part of [Knight](https://github.com/energia-source/knight). Any
support requests, bug reports, or development contributions should be directed to
that project.

## Installation

To begin, install the preferred dependency manager for PHP, [Composer](https://getcomposer.org/).

Now to install just this component:

```sh

$ composer require knight/artifact-iam

```

## Configuration

Configuration is grouped into configuration namespace by the framework [Knight](https://github.com/energia-source/knight).
The configuration files are stored in the configurations folder and file named IAM.php.

So the basic setup looks something like this:

```

<?PHP

namespace configurations;

use Knight\Lock;

use IAM\Configuration as Define;

final class IAM
{
	use Lock;

	const PARAMETERS = [
		// application basename set on identity and access management
		Define::CONFIGURATION_APPLICATION_BASENAME => 'myapplicationame',
		// application key set on identity and access management
		Define::CONFIGURATION_APPLICATION_KEY => '000000000000',
		// server endpoint to connect identity and access management
		Define::CONFIGURATION_HOST_IAM => 'https://iam.energia-europa.com/',
		// cookie name for this site
		Define::CONFIGURATION_COOKIE_NAME => 'alive',
		// policy separator defined on identity and access management
		Define::CONFIGURATION_POLICY_SEPARATOR => '/'
	];
}

```

## Structure

library:
- [IAM](https://github.com/energia-source/knight-artifact-iam/tree/main/lib)

## Usage

So the basic usage looks something like this:

```

<?PHP

namespace what\you\want;

use Knight\armor\Output;

use IAM\Sso;
use IAM\Configuration as IAMConfiguration;

$application_basename = IAMConfiguration::getApplicationBasename();
if (Sso::youHaveNoPolicies($application_basename . '/document/output/action/delete')) Output::print(false);

// what you want if have policy

```

## Built With

* [PHP](https://www.php.net/) - PHP

## Contributing

Please read [CONTRIBUTING.md](https://github.com/energia-source/knight-artifact-iam/blob/main/CONTRIBUTING.md) for details on our code of conduct, and the process for submitting us pull requests.

## Versioning

We use [SemVer](https://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/energia-source/knight-artifact-iam/tags). 

## Authors

* **Paolo Fabris** - *Initial work* - [energia-europa.com](https://www.energia-europa.com/)
* **Gabriele Luigi Masero** - *Developer* - [energia-europa.com](https://www.energia-europa.com/)

See also the list of [contributors](https://github.com/energia-source/knight-artifact-iam/blob/main/CONTRIBUTORS.md) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details
