<?php
declare(strict_types = 1);
namespace Klapuch\Routing;

use Klapuch\Uri;

/**
 * Routes by HTTP method
 */
final class HttpMethodRoutes implements Routes {
	private $origin;
	private $method;

	public function __construct(Routes $origin, string $method) {
		$this->origin = $origin;
		$this->method = $method;
	}

	public function matches(Uri\Uri $uri): array {
		return array_intersect_key(
			$this->origin->matches($uri),
			array_flip(
				preg_grep(
					sprintf('~\[%s\]$~i', $this->method),
					array_keys($this->origin->matches($uri))
				)
			)
		);
	}
}
