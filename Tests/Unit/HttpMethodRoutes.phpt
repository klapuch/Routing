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
			))->matches(new Uri\FakeUri())
		);
	}

	public function testFilteringByCaseInsensitiveMethods() {
		Assert::same(
			['foo [post]' => 'a'],
			(new Routing\HttpMethodRoutes(
				new Routing\FakeRoutes(['foo [post]' => 'a']),
				'POST'
			))->matches(new Uri\FakeUri())
		);
		Assert::same(
			['foo [POST]' => 'a'],
			(new Routing\HttpMethodRoutes(
				new Routing\FakeRoutes(['foo [POST]' => 'a']),
				'post'
			))->matches(new Uri\FakeUri())
		);
	}

	public function testNotMatchingForDifferentMethod() {
		Assert::same(
			[],
			(new Routing\HttpMethodRoutes(
				new Routing\FakeRoutes(['foo [GET]' => 'a']),
				'POST'
			))->matches(new Uri\FakeUri())
		);
	}
}


(new HttpMethodRoutes())->run();
