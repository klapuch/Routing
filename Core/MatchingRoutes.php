<?php
declare(strict_types = 1);
namespace Klapuch\Routing;

use Klapuch\Uri;

/**
 * Matching routes
 */
final class MatchingRoutes implements Routes {
	private $origin;
	private $method;

	public function __construct(Routes $origin, string $method) {
		$this->origin = $origin;
		$this->method = $method;
	}

	public function matches(Uri\Uri $uri): array {
		$matches = $this->origin->matches($uri);
		if ($matches)
			return $matches;
		throw new \UnexpectedValueException(
			sprintf(
				'%s as %s method is not matching to any listed routes',
				$uri->path(),
				strtoupper($this->method)
			)
		);
	}
}
