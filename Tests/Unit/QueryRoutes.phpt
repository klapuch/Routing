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
				'{foo :string}?name=cool [GET]' => 'a',
				'{foo (\w\d+*[])}?name=cool' => 'b',
				'bar?name=cool [GET]' => 'd',
			],
			(new Routing\QueryRoutes(
				new Routing\FakeRoutes(
					[
						'{foo :string}?name=cool [GET]' => 'a',
						'{foo (\w\d+*[])}?name=cool' => 'b',
						'foo?name=not_cool [GET]' => 'c',
						'bar?name=cool [GET]' => 'd',
					]
				),
				new Uri\FakeUri(null, null, ['name' => 'cool'])
			))->matches()
		);
	}

	public function testMatchingWithRandomOrder() {
		Assert::same(
			['foo?name=cool&position=developer [GET]' => 'a'],
			(new Routing\QueryRoutes(
				new Routing\FakeRoutes(
					['foo?name=cool&position=developer [GET]' => 'a']
				),
				new Uri\FakeUri(null, null, ['position' => 'developer', 'name' => 'cool'])
			))->matches()
		);
	}

	public function testIgnoringOtherNotRelevantParameters() {
		Assert::same(
			['foo?name=cool [GET]' => 'a'],
			(new Routing\QueryRoutes(
				new Routing\FakeRoutes(['foo?name=cool [GET]' => 'a']),
				new Uri\FakeUri(null, null, ['position' => 'developer', 'name' => 'cool'])
			))->matches()
		);
	}

	public function testNotMatchingForDifferentParameterName() {
		Assert::same(
			[],
			(new Routing\QueryRoutes(
				new Routing\FakeRoutes(['foo?name=cool [GET]' => 'a']),
				new Uri\FakeUri(null, null, ['x' => 'cool'])
			))->matches()
		);
	}

	public function testCheckingOnlyQueryPart() {
		Assert::same(
			[],
			(new Routing\QueryRoutes(
				new Routing\FakeRoutes(['foo?name=cool [GET]' => 'a']),
				new Uri\FakeUri(null, 'bar', ['name' => 'x'])
			))->matches()
		);
	}
}


(new QueryRoutes())->run();
