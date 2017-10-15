<?php
declare(strict_types = 1);
namespace Klapuch\Routing;

use Klapuch\Uri;

/**
 * Fake
 */
final class FakeRoutes implements Routes {
	private $routes;

	public function __construct(array $routes = null) {
		$this->routes = $routes;
	}

	public function matches(Uri\Uri $uri): array {
		return $this->routes;
	}
}