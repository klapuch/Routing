<?php
declare(strict_types = 1);
namespace Klapuch\Routing;

use Klapuch\Uri;

/**
 * Routes following regex
 */
final class RegexRoutes implements Routes {
	private $origin;

	public function __construct(Routes $origin) {
		$this->origin = $origin;
	}

	public function matches(Uri\Uri $uri): array {
		$matches = array_filter(
			$this->patterns($this->origin->matches($uri)),
			function(string $source) use ($uri): bool {
				return (bool) preg_match(
					sprintf('~^%s$~i', strtok($source, ' ')),
					$uri->path()
				);
			},
			ARRAY_FILTER_USE_KEY
		);
		return array_intersect_key(
			$this->origin->matches($uri),
			array_flip(
				array_filter(
					array_filter(
						array_keys($this->origin->matches($uri)),
						function(string $match) use ($matches): bool {
							return array_search($match, $matches) !== false;
						}
					)
				)
			)
		);
	}

	/**
	 * All the variable placeholders replaced by patterns
	 * @param array $matches
	 * @return array
	 */
	private function patterns(array $matches): array {
		return array_combine(
			array_map([$this, 'filling'], array_keys($matches)),
			array_keys($matches)
		);
	}

	// @codingStandardsIgnoreStart Used by array_map
	/**
	 * Filling variable placeholders
	 * @param string $match
	 * @return string
	 */
	private function filling(string $match): string {
		preg_match('~\s(\[\w+\])~', $match, $method);
		return implode(
			'/',
			array_map(
				[$this, 'pattern'],
				explode('/', str_replace(current($method), '', $match))
			)
		) . current($method);
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
