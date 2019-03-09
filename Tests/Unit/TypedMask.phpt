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
	public function testTypeAwareParameters() {
		Assert::same(
			['a' => 1, 'b' => 123456789, 'c' => '1ab', 'd' => 'ba1', 'e' => '1,2'],
			(new Routing\TypedMask(
				new Routing\FakeMask(
					[
						'a' => '1',
						'b' => '123456789',
						'c' => '1ab',
						'd' => 'ba1',
						'e' => '1,2',
					]
				)
			))->parameters()
		);
	}
}


(new TypedMask())->run();
