<?php
declare(strict_types = 1);
namespace Klapuch\Routing;

use Hashids\HashidsInterface;

/**
 * Mask using hashid
 */
final class HashIdMask implements Mask {
	private $origin;
	private $parameters;
	private $hashids;

	public function __construct(Mask $origin, array $parameters, HashidsInterface $hashids) {
		$this->origin = $origin;
		$this->parameters = $parameters;
		$this->hashids = $hashids;
	}

	public function parameters(): array {
		return array_map(
			function(string $hash): int {
				return current($this->hashids->decode($hash));
			},
			array_intersect_key($this->origin->parameters(), array_flip($this->parameters))
		) + $this->origin->parameters();
	}
}