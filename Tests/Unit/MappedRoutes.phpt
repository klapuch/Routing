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

final class MappedRoutes extends Tester\TestCase {
	public function testMappingToCustomClass() {
		$routes = (new Routing\MappedRoutes(
			new Routing\FakeRoutes(
				[
					'foo/{name :int} [GET]' => 'a',
					'bar/{name :int} [GET]' => 'a',
				]
			),
			function(array $match): Routing\Route {
				return new Routing\HttpRoute(
					key($match),
					current($match),
					new Uri\FakeUri()
				);
			}
		))->matches(new Uri\FakeUri());
		Assert::equal(
			[
				'foo/{name :int} [GET]' => new Routing\HttpRoute('foo/{name :int} [GET]', 'a', new Uri\FakeUri()),
				'bar/{name :int} [GET]' => new Routing\HttpRoute('bar/{name :int} [GET]', 'a', new Uri\FakeUri()),
			],
			$routes
		);
	}
}


(new MappedRoutes())->run();