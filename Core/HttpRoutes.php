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
		$matches = array_filter(
			preg_replace('~{\w+}~', '[\w\d]+', $this->choices->read()),
			function(string $source) use($uri): bool {
				return (bool) preg_match(
					sprintf('~^%s$~iu', $source),
					$uri->path()
				);
			}
		);
		if ($matches) {
			return (string) key($matches);
		}
		throw new \UnexpectedValueException('HTTP route does not exist');
	}
}