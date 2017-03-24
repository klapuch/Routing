<?php
declare(strict_types = 1);
namespace Klapuch\Routing;

use Klapuch\Ini;
use Klapuch\Uri;

/**
 * Routes suitable for HTTP protocol
 */
final class HttpRoutes implements Routes {
	private $ini;

	public function __construct(Ini\Ini $ini) {
		$this->ini = $ini;
	}

	public function match(Uri\Uri $uri): Route {
		$choices = $this->ini->read();
		$matches = array_filter(
			preg_replace('~{\w+}~', '[\w\d]+', $choices),
			function(string $source) use($uri): bool {
				return (bool) preg_match(
					sprintf('~^%s$~iu', $source),
					$uri->path()
				);
			}
		);
		if ($matches) {
			return new HttpRoute(
				$choices[key($matches)],
				(string) key($matches),
				$uri
			);
		}
		throw new \UnexpectedValueException('HTTP route does not exist');
	}
}