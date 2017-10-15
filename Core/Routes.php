<?php
declare(strict_types = 1);
namespace Klapuch\Routing;

use Klapuch\Uri;

interface Routes {
	/**
	 * The matched routes
	 * @param \Klapuch\Uri\Uri $uri
	 * @return array
	 */
	public function matches(Uri\Uri $uri): array;
}