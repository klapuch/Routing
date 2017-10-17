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

final class TypedRoute extends Tester\TestCase {
	public function testTypeAwareParameters() {
		Assert::same(
			['a' => 1, 'b' => 123456789, 'c' => '1ab', 'd' => 'ba1'],
			(new Routing\TypedRoute(
				new Routing\FakeRoute(
					null,
					null,
					[
						'a' => '1',
						'b' => '123456789',
						'c' => '1ab',
						'd' => 'ba1',
					]
				)
			))->parameters()
		);
	}
}


(new TypedRoute())->run();