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

final class HttpMethodRoutes extends Tester\TestCase {
	public function testFilteringByMethods() {
		Assert::same(
			['foo [GET]' => 'a'],
			(new Routing\HttpMethodRoutes(
				new Routing\FakeRoutes(
					[
						'foo [GET]' => 'a',
						'foo [POST]' => 'b',
					]
				),
				'GET'
			))->matches()
		);
	}

	public function testFilteringByCaseInsensitiveMethods() {
		Assert::same(
			['foo [post]' => 'a'],
			(new Routing\HttpMethodRoutes(
				new Routing\FakeRoutes(['foo [post]' => 'a']),
				'POST'
			))->matches()
		);
		Assert::same(
			['foo [POST]' => 'a'],
			(new Routing\HttpMethodRoutes(
				new Routing\FakeRoutes(['foo [POST]' => 'a']),
				'post'
			))->matches()
		);
	}

	public function testNotMatchingForDifferentMethod() {
		Assert::same(
			[],
			(new Routing\HttpMethodRoutes(
				new Routing\FakeRoutes(['foo [GET]' => 'a']),
				'POST'
			))->matches()
		);
	}
}


(new HttpMethodRoutes())->run();
