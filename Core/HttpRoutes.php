<?php
declare(strict_types = 1);
namespace Klapuch\Routing;

use Klapuch\Ini;
use Klapuch\Uri;

/**
 * Routes suitable for HTTP protocol
 */
final class HttpRoutes implements Routes {
	private $choices;

	public function __construct(Ini\Ini $choices) {
		$this->choices = $choices;
	}

	public function match(Uri\Uri $uri): string {
		$match = array_search(
			mb_strtolower($uri->path()),
			array_map('mb_strtolower', $this->choices->read()),
			true
		);
		if ($match === false)
			throw new \UnexpectedValueException('HTTP route does not exist');
		return (string) $match;
	}
}