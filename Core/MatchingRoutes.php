<?php
declare(strict_types = 1);
namespace Klapuch\Routing;

use Klapuch\Uri;

/**
 * Matching routes
 */
final class MatchingRoutes implements Routes {
	private $origin;

	public function __construct(Routes $origin) {
		$this->origin = $origin;
	}

	public function matches(Uri\Uri $uri): array {
		$matches = $this->origin->matches($uri);
		if ($matches)
			return $matches;
		throw new \UnexpectedValueException(
			sprintf('%s is not matching to any listed routes', $uri->path())
		);
	}
}
