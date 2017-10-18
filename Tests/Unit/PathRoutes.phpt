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

final class PathRoutes extends Tester\TestCase {
	public function testPassingWithMatchingRegex() {
		Assert::same(
			['foo/{name \d+}' => 'a'],
			(new Routing\PathRoutes(
				new Routing\FakeRoutes(['foo/{name \d+}' => 'a']),
				new Uri\FakeUri(null, 'foo/123')
			))->matches()
		);
	}

	public function testCaseInsensitiveMatch() {
		Assert::same(
			['foo/{name \d+} [GET]' => 'a'],
			(new Routing\PathRoutes(
				new Routing\FakeRoutes(['foo/{name \d+} [GET]' => 'a']),
				new Uri\FakeUri(null, 'FOO/123')
			))->matches()
		);
		Assert::same(
			['FOO/{name \d+} [GET]' => 'a'],
			(new Routing\PathRoutes(
				new Routing\FakeRoutes(['FOO/{name \d+} [GET]' => 'a']),
				new Uri\FakeUri(null, 'foo/123')
			))->matches()
		);
	}

	public function testRemovingNotMatching() {
		Assert::same(
			[],
			(new Routing\PathRoutes(
				new Routing\FakeRoutes(['foo/{name \d+} [GET]' => 'a']),
				new Uri\FakeUri(null, 'foo/abc')
			))->matches()
		);
	}

	public function testFilteringOnlyMatched() {
		Assert::same(
			['foo/{name \d+} [GET]' => 'a'],
			(new Routing\PathRoutes(
				new Routing\FakeRoutes(
					[
						'foo/{name \d+} [GET]' => 'a',
						'bar/{name \w+} [GET]' => 'b',
					]
				),
				new Uri\FakeUri(null, 'foo/123')
			))->matches()
		);
	}

	public function testIgnoringQueryPart() {
		Assert::same(
			['foo/{name \d+}?page=(1) [GET]' => 'a'],
			(new Routing\PathRoutes(
				new Routing\FakeRoutes(['foo/{name \d+}?page=(1) [GET]' => 'a']),
				new Uri\FakeUri(null, 'foo/123')
			))->matches()
		);
	}

	public function testIgnoringMethodPart() {
		Assert::same(
			['foo/{name \d+} [GET]' => 'a'],
			(new Routing\PathRoutes(
				new Routing\FakeRoutes(['foo/{name \d+} [GET]' => 'a']),
				new Uri\FakeUri(null, 'foo/123')
			))->matches()
		);
	}

	/**
	 * @dataProvider defaultRegexes
	 */
	public function testDefaultRegexes(string $path) {
		Assert::same(
			['foo/{name}' => 'a'],
			(new Routing\PathRoutes(
				new Routing\FakeRoutes(['foo/{name}' => 'a']),
				new Uri\FakeUri(null, $path)
			))->matches()
		);
		Assert::same(
			['foo/{name} [GET]' => 'a'],
			(new Routing\PathRoutes(
				new Routing\FakeRoutes(['foo/{name} [GET]' => 'a']),
				new Uri\FakeUri(null, $path)
			))->matches()
		);
	}

	protected function defaultRegexes() {
		return [
			['foo/abc'],
			['foo/123'],
			['foo/a2c'],
		];
	}
}


(new PathRoutes())->run();
