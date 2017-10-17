<?php
declare(strict_types = 1);
namespace Klapuch\Routing;

use Klapuch\Uri;

/**
 * Route working with query parameters only
 */
final class QueryRoute implements Route {
	private $origin;
	private $uri;

	public function __construct(Route $origin, Uri\Uri $uri) {
		$this->origin = $origin;
		$this->uri = $uri;
	}

	public function resource(): string {
		return $this->origin->resource();
	}

	public function action(): string {
		return $this->origin->action();
	}

	public function parameters(): array {
		return $this->uri->query() + array_map(
			function(string $parameter): string {
				return substr($parameter, 1, -1);
			},
			$this->origin->parameters()
		);
	}
}