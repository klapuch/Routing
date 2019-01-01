<?php
declare(strict_types = 1);

namespace Klapuch\Routing;

/**
 * Fake
 */
final class FakeMask implements Mask {
	/** @var mixed[]|null */
	private $parameters;

	public function __construct(?array $parameters = null) {
		$this->parameters = $parameters;
	}

	public function parameters(): array {
		return $this->parameters;
	}
}
