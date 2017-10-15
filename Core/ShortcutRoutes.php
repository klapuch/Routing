<?php
declare(strict_types = 1);
namespace Klapuch\Routing;

use Klapuch\Uri;

/**
 * Shortcut rules for routes
 */
final class ShortcutRoutes implements Routes {
	private const SHORTCUTS = [
		':int' => '\d+',
		':id' => '[1-9][0-9]*',
		':string' => '\w*\d*',
	];
	private $origin;

	public function __construct(Routes $origin) {
		$this->origin = $origin;
	}

	public function matches(Uri\Uri $uri): array {
		return $this->replacements($this->origin->matches($uri));
	}

	private function replacements(array $matches): array {
		return array_combine(
			array_map(
				function(string $route): string {
					return str_replace(
						array_keys(self::SHORTCUTS),
						self::SHORTCUTS,
						$route
					);
				},
				array_keys($matches)
			),
			$matches
		);
	}
}