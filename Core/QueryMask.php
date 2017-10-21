<?php
declare(strict_types = 1);
namespace Klapuch\Routing;

use Klapuch\Uri;

/**
 * Mask for query
 */
final class QueryMask implements Mask {
	private $source;
	private $uri;

	public function __construct(string $source, Uri\Uri $uri) {
		$this->source = $source;
		$this->uri = $uri;
	}

	public function parameters(): array {
		parse_str((string) parse_url($this->source, PHP_URL_QUERY), $query);
		return $this->uri->query() + array_map(
			function(string $part): string {
					return current(explode(' ', substr($part, 1, -1), 2));
			},
			preg_grep('~^\(.*\)$~', $query)
		) + $query;
	}
}