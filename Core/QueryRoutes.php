<?php
declare(strict_types = 1);
namespace Klapuch\Routing;

use Klapuch\Uri;

/**
 * Routes using query parameters
 */
final class QueryRoutes implements Routes {
	private $origin;
	private $uri;

	public function __construct(Routes $origin, Uri\Uri $uri) {
		$this->origin = $origin;
		$this->uri = $uri;
	}

	public function matches(): array {
		return array_filter(
			$this->origin->matches(),
			function(string $match): bool {
				parse_str(
					parse_url(preg_replace('~\s(\[\w+\])~', '', $match), PHP_URL_QUERY),
					$query
				);
				return array_intersect_assoc($this->uri->query(), $query) == $query; // == intentionally because of order
			},
			ARRAY_FILTER_USE_KEY
		);
	}
}
