<?php
declare(strict_types = 1);

namespace Klapuch\Routing;

/**
 * Mask handling correctly with types
 */
final class TypedMask implements Mask {
	/** @var \Klapuch\Routing\Mask */
	private $origin;

	public function __construct(Mask $origin) {
		$this->origin = $origin;
	}

	public function parameters(): array {
		$parameters = $this->origin->parameters();
		return array_map('intval', array_filter($parameters, 'is_numeric')) + $parameters;
	}
}
