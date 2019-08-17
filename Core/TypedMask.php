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
		$types = [];
		if (isset($_SERVER['ROUTE_TYPE_QUERY'])) {
			parse_str($_SERVER['ROUTE_TYPE_QUERY'], $types);
		}
		$results = [];
		foreach ($this->origin->parameters() as $param => $value) {
			switch ($types[$param] ?? 'string') {
				case 'int':
					$results[$param] = (int) $value;
					break;
				default:
					$results[$param] = $value;
			}
		}
		return $results;
	}
}
