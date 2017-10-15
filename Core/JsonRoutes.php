<?php
declare(strict_types = 1);
namespace Klapuch\Routing;

use Klapuch\Uri;

/**
 * Routes loaded as JSON
 */
final class JsonRoutes implements Routes {
	private $json;

	public function __construct(\SplFileInfo $json) {
		$this->json = $json;
	}

	public function matches(Uri\Uri $uri): array {
		if ($this->json->isFile())
			return json_decode(file_get_contents($this->json->getPathname()), true);
		throw new \UnexpectedValueException(
			sprintf(
				'Routes in JSON as %s does not exist',
				$this->json->getPathname()
			)
		);
	}
}
