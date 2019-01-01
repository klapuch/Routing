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

final class MatchingRoutes extends Tester\TestCase {
	public function testPassingOnAnyMatchedRoute() {
		Assert::same(
			['a' => 'b'],
			(new Routing\MatchingRoutes(
				new Routing\FakeRoutes(['a' => 'b']),
				new Uri\FakeUri(),
				'GET'
			))->matches()
		);
	}

	/**
	 * @throws \UnexpectedValueException /foo/bar as GET method is not matching to any listed routes
	 */
	public function testThrowingOnNothingToMatch() {
		(new Routing\MatchingRoutes(
			new Routing\FakeRoutes([]),
			new Uri\FakeUri(null, '/foo/bar'),
			'get'
		))->matches();
	}
}


(new MatchingRoutes())->run();
