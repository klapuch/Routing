<?php
declare(strict_types = 1);

namespace Klapuch\Routing;

/**
 * Routes matching by returned nginx rules
 */
final class NginxMatchedRoutes implements Routes {
	/** @var \Klapuch\Routing\Routes */
	private $origin;

	public function __construct(Routes $origin) {
		$this->origin = $origin;
	}

	public function matches(): array {
		$matches = $this->origin->matches();
		$path = sprintf('%s [%s]', $_SERVER['ROUTE_NAME'], $_SERVER['REQUEST_METHOD']);
		if (isset($matches[$path]))
			return [$_SERVER['ROUTE_NAME'] => $matches[$path]];
		return [];
	}
}
