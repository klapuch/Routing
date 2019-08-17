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

final class TypedMask extends Tester\TestCase {
	public function testParsingByPredefinedTypes() {
		$_SERVER['ROUTE_TYPE_QUERY'] = 'a=int&b=string';
		Assert::same(
			['a' => 1, 'b' => '123456789', 'c' => 'test'],
			(new Routing\TypedMask(
				new Routing\FakeMask(
					[
						'a' => '1',
						'b' => '123456789',
						'c' => 'test',
					]
				)
			))->parameters()
		);
	}
}


(new TypedMask())->run();
