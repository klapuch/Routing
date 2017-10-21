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
			function(array $match): Routing\Mask {
				return new Routing\DefaultMask(
					key($match),
					new Uri\FakeUri()
				);
			}
		))->matches();
		Assert::equal(
			[
				'foo/{name :int} [GET]' => new Routing\DefaultMask('foo/{name :int} [GET]', new Uri\FakeUri()),
				'bar/{name :int} [GET]' => new Routing\DefaultMask('bar/{name :int} [GET]', new Uri\FakeUri()),
			],
			$routes
		);
	}
}


(new MappedRoutes())->run();
