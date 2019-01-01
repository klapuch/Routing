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

final class CachedMask extends TestCase\Mockery {
	public function testMultipleCallsWithSingleExecution() {
		$origin = $this->mock(Routing\Mask::class);
		$origin->shouldReceive('parameters')->once();
		$routes = new Routing\CachedMask($origin);
		Assert::equal($routes->parameters(), $routes->parameters());
	}
}

(new CachedMask())->run();
