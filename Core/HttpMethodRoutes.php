<?php
declare(strict_types = 1);

namespace Klapuch\Routing;

/**
 * Routes matching only HTTP methods
 */
final class HttpMethodRoutes implements Routes {
	/** @var \Klapuch\Routing\CachedRoutes */
	private $origin;

	/** @var string */
	private $method;

	public function __construct(Routes $origin, string $method) {
		$this->origin = new CachedRoutes($origin);
		$this->method = $method;
	}

	public function matches(): array {
		$matches = array_intersect_key(
			$this->origin->matches(),
			array_flip(
				preg_grep(
					$this->pattern($this->method),
					array_keys($this->origin->matches())
				)
			)
		);
		return (array) array_combine(
			(array) preg_replace(
				$this->pattern($this->method),
				'',
				array_keys($matches)
			),
			$matches
		);
	}

	/**
	 * Pattern matching method
	 * @param string $method
	 * @return string
	 */
	private function pattern(string $method): string {
		return sprintf('~\s+\[%s\]$~i', $method);
	}
}
