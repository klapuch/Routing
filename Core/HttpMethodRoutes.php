<?php
declare(strict_types = 1);
namespace Klapuch\Routing;

/**
 * Routes matching HTTP methods
 */
final class HttpMethodRoutes implements Routes {
	private $origin;
	private $method;

	public function __construct(Routes $origin, string $method) {
		$this->origin = $origin;
		$this->method = $method;
	}

	public function matches(): array {
		return array_intersect_key(
			$this->origin->matches(),
			array_flip(
				preg_grep(
					sprintf('~\[%s\]$~i', $this->method),
					array_keys($this->origin->matches())
				)
			)
		);
	}
}
