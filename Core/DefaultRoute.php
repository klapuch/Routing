<?php
declare(strict_types = 1);
namespace Klapuch\Routing;

use Klapuch\Uri;

/**
 * Default root for Route
 */
final class DefaultRoute implements Route {
	private const SEPARATOR = '/';
	private const RESOURCE = 0,
		ACTION = 1;
	private $source;
	private $destination;
	private $uri;

	public function __construct(string $source, string $destination, Uri\Uri $uri) {
		$this->source = $source;
		$this->destination = $destination;
		$this->uri = $uri;
	}

	public function resource(): string {
		return explode(self::SEPARATOR, $this->destination, 2)[self::RESOURCE];
	}

	public function action(): string {
		return explode(self::SEPARATOR, $this->destination, 2)[self::ACTION] ?? '';
	}

	public function parameters(): array {
		parse_str((string) parse_url($this->source, PHP_URL_QUERY), $query);
		return $query + $this->path($this->source, $this->uri);
	}

	private function path(string $source, Uri\Uri $uri): array {
		$sources = explode(self::SEPARATOR, parse_url($source, PHP_URL_PATH));
		$parameters = array_diff(explode(self::SEPARATOR, $uri->path()), $sources);
		return array_combine(
			preg_replace(
				'~{|}|\s\S+~',
				'',
				array_intersect_key($sources, $parameters)
			),
			$parameters
		);
	}
}