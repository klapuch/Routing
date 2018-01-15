<?php
declare(strict_types = 1);
/**
 * @testCase
 * @phpVersion > 7.1
 */
namespace Klapuch\Routing\Unit;

use Hashids\Hashids;
use Klapuch\Routing;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

final class HashIdMask extends Tester\TestCase {
	public function testDecodingDefinedParameter() {
		Assert::same(
			['id' => 1, 'name' => 'foo'],
			(new Routing\HashIdMask(
				new Routing\FakeMask(['id' => 'jR', 'name' => 'foo']),
				['id'],
				new Hashids()
			))->parameters()
		);
	}

	/**
	 * @throws \UnexpectedValueException Mask contains these broken hashes: foo
	 */
	public function testThrowingOnBrokenHash() {
		(new Routing\HashIdMask(
			new Routing\FakeMask(['id' => 'foo', 'name' => 'bar']),
			['id'],
			new Hashids()
		))->parameters();
	}

	/**
	 * @throws \UnexpectedValueException Mask contains these broken hashes: foo, bar
	 */
	public function testThrowingOnMultipleBrokenHashes() {
		(new Routing\HashIdMask(
			new Routing\FakeMask(['id' => 'foo', 'name' => 'bar', 'good' => 'jR']),
			['id', 'name', 'good'],
			new Hashids()
		))->parameters();
	}
}


(new HashIdMask())->run();