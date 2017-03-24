<?php
declare(strict_types = 1);
namespace Klapuch\Routing;

use Klapuch\Uri;

interface Routes {
	/**
	 * The matched route
	 * @param \Klapuch\Uri\Uri $uri
	 * @return \Klapuch\Routing\Route
	 */
	public function match(Uri\Uri $uri): Route;
}