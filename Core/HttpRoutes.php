<?php
declare(strict_types = 1);
namespace Klapuch\Routing;

use Klapuch\Ini;
use Klapuch\Uri;

/**
 * Routes suitable for HTTP protocol
 */
final class HttpRoutes implements Routes {
	private $ini;

	public function __construct(Ini\Source $ini) {
		$this->ini = $ini;
	}

	public function match(Uri\Uri $uri): Route {
		$choices = $this->ini->read();
		$matches = array_filter(
			$this->patterns($choices),
			function(string $source) use ($uri): bool {
				return (bool) preg_match(
					sprintf('~^%s$~iu', $source),
					$uri->path()
				);
			}
		);
		if ($matches) {
			return new HttpRoute(
				$choices[key($matches)],
				(string) key($matches),
				$uri
			);
		}
		throw new \UnexpectedValueException(
			sprintf('HTTP route for "%s" does not exist', $uri->path())
		);
	}

	/**
	 * All the variable placeholders replaced by patterns
	 * @param array $choices
	 * @return array
	 */
	private function patterns(array $choices): array {
		return array_combine(
			array_keys($choices),
			array_map([$this, 'filling'], $choices)
		);
	}

	// @codingStandardsIgnoreStart Used by array_map
	/**
	 * Filling variable placeholders
	 * @param string $choice
	 * @return string
	 */
	private function filling(string $choice): string {
		return implode(
			'/',
			array_map([$this, 'pattern'], explode('/', $choice))
		);
	}

	/**
	 * Placeholder replaced by pattern
	 * @param string $part
	 * @return string
	 */
	private function pattern(string $part): string {
		return preg_replace(
			'~{.+}~',
			rtrim(explode(' ', $part)[1] ?? '[\w\d]+', '}'),
			$part
		);
	} // @codingStandardsIgnoreEnd
}