<?php
declare(strict_types = 1);

namespace Klapuch\Routing;

/**
 * Shortcut replacements for routes
 */
final class ShortcutRoutes implements Routes {
	private const SHORTCUTS = [
		':int' => '\d+',
		':id' => '[1-9][0-9]*',
		':string' => '\w*\d*',
	];

	/** @var \Klapuch\Routing\CachedRoutes */
	private $origin;

	public function __construct(Routes $origin) {
		$this->origin = new CachedRoutes($origin);
	}

	public function matches(): array {
		return (array) array_combine(
			array_map(
				static function(string $route): string {
					return str_replace(
						array_keys(self::SHORTCUTS),
						self::SHORTCUTS,
						$route
					);
				},
				array_keys($this->origin->matches())
			),
			$this->origin->matches()
		);
	}
}
