<?php
declare(strict_types = 1);

namespace Klapuch\Routing;

/**
 * Mask behaving as a huge single one
 */
final class CombinedMask implements Mask {
	/** @var \Klapuch\Routing\Mask[] */
	private $origins;

	public function __construct(Mask ...$origins) {
		$this->origins = $origins;
	}

	public function parameters(): array {
		return array_reduce(
			$this->origins,
			static function(array $merge, Mask $origin) {
				return $merge + $origin->parameters();
			},
			[]
		);
	}
}
