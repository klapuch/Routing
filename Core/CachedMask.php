<?php
declare(strict_types = 1);

namespace Klapuch\Routing;

/**
 * Cached
 */
final class CachedMask implements Mask {
	/** @var \Klapuch\Routing\Mask */
	private $origin;

	/** @var mixed[]|null */
	private $parameters;

	public function __construct(Mask $origin) {
		$this->origin = $origin;
	}

	public function parameters(): array {
		if ($this->parameters === null)
			$this->parameters = $this->origin->parameters();
		return $this->parameters;
	}
}
