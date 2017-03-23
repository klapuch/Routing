<?php
declare(strict_types = 1);
namespace Klapuch\Routing;

use Klapuch\Uri;

/**
 * Direct destination within class name, method and parameters
 */
final class HttpRoute implements Route {
	private const DELIMITER = '/';
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
		return explode(self::DELIMITER, $this->destination, 2)[self::RESOURCE];
	}

	public function action(): string {
		return explode(self::DELIMITER, $this->destination, 2)[self::ACTION];
	}

	public function parameters(): array {
		$parameters = array_values(
			array_diff(
				explode(self::DELIMITER, $this->uri->path()),
				explode(self::DELIMITER, $this->source)
			)
		);
		return array_map('intval', array_filter($parameters, 'is_numeric'))
			+ $parameters;
	}
}