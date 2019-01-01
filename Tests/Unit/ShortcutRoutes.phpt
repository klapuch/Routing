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

final class ShortcutRoutes extends Tester\TestCase {
	public function testShortcutForInt() {
		Assert::same(
			['foo/{name \d+} [GET]' => 'a'],
			(new Routing\ShortcutRoutes(
				new Routing\FakeRoutes(['foo/{name :int} [GET]' => 'a'])
			))->matches()
		);
	}

	public function testShortcutForId() {
		Assert::same(
			['foo/{name [1-9][0-9]*} [GET]' => 'a'],
			(new Routing\ShortcutRoutes(
				new Routing\FakeRoutes(['foo/{name :id} [GET]' => 'a'])
			))->matches()
		);
	}

	public function testShortcutForString() {
		Assert::same(
			['foo/{name \w*\d*} [GET]' => 'a'],
			(new Routing\ShortcutRoutes(
				new Routing\FakeRoutes(['foo/{name :string} [GET]' => 'a'])
			))->matches()
		);
	}

	public function testPassingWithMultipleCombinations() {
		Assert::same(
			[
				'foo/{name \d+} [GET]' => 'a',
				'foo/{name \w*\d*} [GET]' => 'b',
			],
			(new Routing\ShortcutRoutes(
				new Routing\FakeRoutes(
					[
						'foo/{name :int} [GET]' => 'a',
						'foo/{name :string} [GET]' => 'b',
					]
				)
			))->matches()
		);
	}
}


(new ShortcutRoutes())->run();
