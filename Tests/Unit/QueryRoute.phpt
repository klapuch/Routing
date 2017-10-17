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

final class QueryRoute extends Tester\TestCase {
	public function testUsingDefaultValueOnNotContainingInQuery() {
		Assert::same(
			['mood' => 'good'],
			(new Routing\QueryRoute(
				new Routing\FakeRoute(null, null, ['mood' => '(good)']),
				new Uri\FakeUri(null, null, [])
			))->parameters()
		);
	}

	public function testNotOverwritingForAlreadyState() {
		Assert::same(
			['page' => 2],
			(new Routing\QueryRoute(
				new Routing\FakeRoute(null, null, ['page' => '(1)']),
				new Uri\FakeUri(null, null, ['page' => 2])
			))->parameters()
		);
	}

	public function testComplementaryValue() {
		Assert::same(
			['mood' => 'good'],
			(new Routing\QueryRoute(
				new Routing\FakeRoute(null, null, ['mood' => 'good']),
				new Uri\FakeUri(null, null, ['mood' => 'good'])
			))->parameters()
		);
	}

	public function testBracesAsRegularValue() {
		Assert::same(
			['mood' => '(good)'],
			(new Routing\QueryRoute(
				new Routing\FakeRoute(null, null, ['mood' => '((good))']),
				new Uri\FakeUri(null, null, [])
			))->parameters()
		);
	}
}


(new QueryRoute())->run();