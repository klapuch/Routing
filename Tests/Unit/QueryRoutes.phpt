<?php
declare(strict_types = 1);

/**
 * @testCase
 * @phpVersion > 7.1
 */

namespace Klapuch\Routing\Unit;

use Klapuch\Routing;
use Klapuch\Uri;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

final class QueryRoutes extends Tester\TestCase {
	public function testMatchingAnyWithMatchingQuery() {
		Assert::same(
			[
				'{foo :string}?name=cool' => 'a',
				'{foo (\w\d+*[])}?name=cool' => 'b',
				'bar?name=cool' => 'd',
			],
			(new Routing\QueryRoutes(
				new Routing\FakeRoutes(
					[
						'{foo :string}?name=cool' => 'a',
						'{foo (\w\d+*[])}?name=cool' => 'b',
						'foo?name=not_cool' => 'c',
						'bar?name=cool' => 'd',
					]
				),
				new Uri\FakeUri(null, null, ['name' => 'cool'])
			))->matches()
		);
	}

	public function testMatchingWithRandomOrder() {
		Assert::same(
			['foo?name=cool&position=developer' => 'a'],
			(new Routing\QueryRoutes(
				new Routing\FakeRoutes(
					['foo?name=cool&position=developer' => 'a']
				),
				new Uri\FakeUri(null, null, ['position' => 'developer', 'name' => 'cool'])
			))->matches()
		);
	}

	public function testIgnoringOtherNotRelevantParameters() {
		Assert::same(
			['foo?name=cool' => 'a'],
			(new Routing\QueryRoutes(
				new Routing\FakeRoutes(['foo?name=cool' => 'a']),
				new Uri\FakeUri(null, null, ['position' => 'developer', 'name' => 'cool'])
			))->matches()
		);
	}

	public function testNotMatchingForDifferentParameterName() {
		Assert::same(
			[],
			(new Routing\QueryRoutes(
				new Routing\FakeRoutes(['foo?name=cool' => 'a']),
				new Uri\FakeUri(null, null, ['x' => 'cool'])
			))->matches()
		);
	}

	public function testBracesForDefaultValue() {
		Assert::same(
			['foo?page=(1)' => 'a'],
			(new Routing\QueryRoutes(
				new Routing\FakeRoutes(['foo?page=(1)' => 'a']),
				new Uri\FakeUri(null, null, [])
			))->matches()
		);
	}

	public function testPassingWithDefaultAndRegex() {
		Assert::same(
			[
				'foo?page=(1 \d+)' => 'a',
				'foo?page=(1 \w*\d*)' => 'c',
				'foo?page=(abc \w*\d*)' => 'd',
			],
			(new Routing\QueryRoutes(
				new Routing\FakeRoutes(
					[
						'foo?page=(1 \d+)' => 'a',
						'foo?page=(abc \s+)' => 'b',
						'foo?page=(1 \w*\d*)' => 'c',
						'foo?page=(abc \w*\d*)' => 'd',
					]
				),
				new Uri\FakeUri(null, null, ['page' => 2])
			))->matches()
		);
	}

	public function testPassingWithoutQuery() {
		Assert::same(
			['foo' => 'a'],
			(new Routing\QueryRoutes(
				new Routing\FakeRoutes(['foo' => 'a']),
				new Uri\FakeUri(null, null, [])
			))->matches()
		);
	}
}


(new QueryRoutes())->run();
