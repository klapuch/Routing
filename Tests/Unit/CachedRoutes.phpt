<?php
declare(strict_types = 1);

/**
 * @testCase
 * @phpVersion > 7.1
 */

namespace Klapuch\Routing\Unit;

use Klapuch\Routing;
use Klapuch\Routing\TestCase;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

final class CachedRoutes extends TestCase\Mockery {
	public function testMultipleCallsWithSingleExecution() {
		$origin = $this->mock(Routing\Routes::class);
		$origin->shouldReceive('matches')->once();
		$routes = new Routing\CachedRoutes($origin);
		Assert::equal($routes->matches(), $routes->matches());
	}
}

(new CachedRoutes())->run();
