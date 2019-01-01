<?php
declare(strict_types = 1);

namespace Klapuch\Routing;

use Klapuch\Uri;

/**
 * Routes matching only query parts
 */
final class QueryRoutes implements Routes {
	/** @var \Klapuch\Routing\Routes */
	private $origin;

	/** @var \Klapuch\Uri\Uri */
	private $uri;

	public function __construct(Routes $origin, Uri\Uri $uri) {
		$this->origin = $origin;
		$this->uri = $uri;
	}

	public function matches(): array {
		return array_filter(
			$this->origin->matches(),
			function(string $match): bool {
				parse_str((string) parse_url($match, PHP_URL_QUERY), $query);
				return $this->includes($this->uri, $query, $this->defaults($query))
					|| ($this->patterns($query) !== [] && $this->allowed($this->patterns($query)));
			},
			ARRAY_FILTER_USE_KEY
		);
	}

	private function allowed(array $patterns): bool {
		$real = array_intersect_key($this->uri->query(), $patterns);
		return array_reduce(
			array_keys($real),
			static function(bool $allowed, string $field) use ($real, $patterns): bool {
					return $allowed && preg_match(
						sprintf('~^%s$~', $patterns[$field]),
						(string) $real[$field]
					) === 1;
			},
			true
		);
	}

	/**
	 * Does the URI includes given query within defaults?
	 * @param \Klapuch\Uri\Uri $uri
	 * @param array $query
	 * @param array $defaults
	 * @return bool
	 */
	private function includes(Uri\Uri $uri, array $query, array $defaults): bool {
		return array_intersect_assoc($defaults + $uri->query(), $defaults + $query) == $defaults + $query; // == intentionally because of order
	}

	/**
	 * Default values extracted from query - everything in brace
	 * @param array $query
	 * @return array
	 */
	private function defaults(array $query): array {
		return array_map(
			static function(string $parameter): string {
				return substr($parameter, 1, -1);
			},
			preg_grep('~\(\w*\d*\)$~', $query)
		);
	}

	/**
	 * Patterns to queries
	 * @param array $query
	 * @return array
	 */
	private function patterns(array $query): array {
		return array_map(
			static function(string $parameter): string {
				preg_match('~\.*\s(.*)$~', substr($parameter, 1, -1), $matches);
				return str_replace(' ', '+', $matches[1]);
			},
			preg_grep('~\.*\s.*$~', $query)
		);
	}
}
