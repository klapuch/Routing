<?php
declare(strict_types = 1);
namespace Klapuch\Routing;

use Klapuch\Uri;

/**
 * Direct destination within class name, method and parameters
 */
final class HttpRoute implements Route {
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
		return explode(self::SEPARATOR, $this->destination, 2)[self::ACTION];
	}

	public function parameters(): array {
		$sources = explode(self::SEPARATOR, $this->source);
		$parameters = array_diff(explode(self::SEPARATOR, $this->uri->path()), $sources);
		return array_combine(
			preg_replace(
				'~{|}|\s\S+~',
				'',
				array_intersect_key($sources, $parameters)
			),
			array_map('intval', array_filter($parameters, 'is_numeric')) + $parameters
		);
	}
}