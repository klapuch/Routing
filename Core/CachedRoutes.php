<?php
declare(strict_types = 1);
namespace Klapuch\Routing;

use Klapuch\Uri;

/**
 * Cached
 */
final class CachedRoutes implements Routes {
	private $origin;
	private $matches;

	public function __construct(Routes $origin) {
		$this->origin = $origin;
	}

	public function matches(Uri\Uri $uri): array {
		if ($this->matches === null)
			$this->matches = $this->origin->matches($uri);
		return $this->matches;
	}
}
