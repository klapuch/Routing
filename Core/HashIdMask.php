<?php
declare(strict_types = 1);
namespace Klapuch\Routing;

use Hashids\HashidsInterface;

/**
 * Mask using hashid
 */
final class HashIdMask implements Mask {
	private $origin;
	private $whitelist;
	private $hashids;

	public function __construct(Mask $origin, array $whitelist, HashidsInterface $hashids) {
		$this->origin = new CachedMask($origin);
		$this->whitelist = $whitelist;
		$this->hashids = $hashids;
	}

	public function parameters(): array {
		$decoded = $this->decoded($this->origin->parameters(), $this->whitelist);
		$broken = $this->broken($decoded, $this->origin->parameters(), $this->whitelist);
		if ($broken) {
			throw new \UnexpectedValueException(
				sprintf('Mask contains these broken hashes: %s', implode(', ', $broken))
			);
		}
		return $decoded + $this->origin->parameters();
	}

	private function broken(array $decoded, array $raw, array $whitelist): array {
		return array_intersect_key(
			array_diff_key($raw, array_filter($decoded, 'is_int')),
			array_flip($whitelist)
		);
	}

	private function decoded(array $parameters, array $whitelist): array {
		return array_map(
			'current',
			array_map(
				[$this->hashids, 'decode'],
				array_intersect_key($parameters, array_flip($whitelist))
			)
		);
	}
}