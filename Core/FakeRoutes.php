<?php
declare(strict_types = 1);

namespace Klapuch\Routing;

/**
 * Fake
 */
final class FakeRoutes implements Routes {
	/** @var mixed[]|null */
	private $routes;

	public function __construct(?array $routes = null) {
		$this->routes = $routes;
	}

	public function matches(): array {
		return $this->routes;
	}
}
