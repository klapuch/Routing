<?php
declare(strict_types = 1);
namespace Klapuch\Routing;

use Klapuch\Uri;

/**
 * Routes mapped to arbitrary Route class
 */
final class MappedRoutes implements Routes {
	private $origin;
	private $map;

	public function __construct(Routes $origin, callable $map) {
		$this->origin = $origin;
		$this->map = $map;
	}

	public function matches(Uri\Uri $uri): array {
		return array_map(
			$this->map,
			array_combine(
				array_keys($this->origin->matches($uri)),
				array_map(
					function($destination, $source): array {
						return [$source => $destination];
					},
					$this->origin->matches($uri),
					array_keys($this->origin->matches($uri))
				)
			)
		);
	}
}
