<?php
declare(strict_types = 1);
namespace Klapuch\Routing;

use Klapuch\Uri;

/**
 * Any matching route
 */
final class MatchingRoutes implements Routes {
	private $origin;
	private $uri;
	private $method;

	public function __construct(Routes $origin, Uri\Uri $uri, string $method) {
		$this->origin = new CachedRoutes($origin);
		$this->uri = $uri;
		$this->method = $method;
	}

	public function matches(): array {
		if ($this->origin->matches())
			return $this->origin->matches();
		throw new \UnexpectedValueException(
			sprintf(
				'%s as %s method is not matching to any listed routes',
				$this->uri->path(),
				strtoupper($this->method)
			)
		);
	}
}
