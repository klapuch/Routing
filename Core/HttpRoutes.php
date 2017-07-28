<?php
declare(strict_types = 1);
namespace Klapuch\Routing;

use Klapuch\Uri;

/**
 * Routes suitable for HTTP protocol
 */
final class HttpRoutes implements Routes {
	private $choices;
	private $method;

	public function __construct(array $choices, string $method) {
		$this->choices = $choices;
		$this->method = $method;
	}

	public function match(Uri\Uri $uri): Route {
		$matches = array_filter(
			$this->patterns($this->choices),
			function(string $source) use ($uri): bool {
				return (bool) preg_match(
					sprintf(
						'~^%s(\s\[%s\])?$~iu',
						preg_replace('~\s\[\w+\]$~', '', $source),
						$this->method
					),
					$uri->path()
				);
			}
		);
		if ($matches) {
			return new HttpRoute(
				(string) key($matches),
				$this->choices[key($matches)],
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
			array_map([$this, 'filling'], array_keys($choices))
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
