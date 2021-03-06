<?php
declare(strict_types = 1);

namespace Klapuch\Routing;

/**
 * Cached
 */
final class CachedRoutes implements Routes {
	/** @var \Klapuch\Routing\Routes */
	private $origin;

	/** @var mixed[]|null */
	private $matches;

	public function __construct(Routes $origin) {
		$this->origin = $origin;
	}

	public function matches(): array {
		if ($this->matches === null)
			$this->matches = $this->origin->matches();
		return $this->matches;
	}
}
