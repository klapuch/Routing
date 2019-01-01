<?php
declare(strict_types = 1);

/**
 * @testCase
 * @phpVersion > 7.1
 */

namespace Klapuch\Routing\Unit;

use Klapuch\Routing;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

final class CombinedMask extends Tester\TestCase {
	public function testPrecedenceByPassedPosition() {
		Assert::same(
			['name' => 'dom', 'position' => 'developer', 'age' => 21],
			(new Routing\CombinedMask(
				new Routing\FakeMask(['name' => 'dom']),
				new Routing\FakeMask(['name' => 'xxx']),
				new Routing\FakeMask(['position' => 'developer']),
				new Routing\FakeMask(['position' => 'xxx']),
				new Routing\FakeMask(['age' => 21])
			))->parameters()
		);
	}
}


(new CombinedMask())->run();
