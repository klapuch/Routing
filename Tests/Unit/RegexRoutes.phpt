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

final class RegexRoutes extends Tester\TestCase {
	public function testPassingWithMatchingRegex() {
		Assert::same(
			['foo/{name \d+} [GET]' => 'a'],
			(new Routing\RegexRoutes(
				new Routing\FakeRoutes(['foo/{name \d+} [GET]' => 'a'])
			))->matches(new Uri\FakeUri(null, 'foo/123'))
		);
	}

	public function testCaseInsensitiveMatch() {
		Assert::same(
			['foo/{name \d+} [GET]' => 'a'],
			(new Routing\RegexRoutes(
				new Routing\FakeRoutes(['foo/{name \d+} [GET]' => 'a'])
			))->matches(new Uri\FakeUri(null, 'FOO/123'))
		);
		Assert::same(
			['FOO/{name \d+} [GET]' => 'a'],
			(new Routing\RegexRoutes(
				new Routing\FakeRoutes(['FOO/{name \d+} [GET]' => 'a'])
			))->matches(new Uri\FakeUri(null, 'foo/123'))
		);
	}

	public function testRemovingNotMatching() {
		Assert::same(
			[],
			(new Routing\RegexRoutes(
				new Routing\FakeRoutes(['foo/{name \d+} [GET]' => 'a'])
			))->matches(new Uri\FakeUri(null, 'foo/abc'))
		);
	}

	public function testFilteringOnlyMatched() {
		Assert::same(
			['foo/{name \d+} [GET]' => 'a'],
			(new Routing\RegexRoutes(
				new Routing\FakeRoutes(
					[
						'foo/{name \d+} [GET]' => 'a',
						'bar/{name \w+} [GET]' => 'b',
					]
				)
			))->matches(new Uri\FakeUri(null, 'foo/123'))
		);
	}

	/**
	 * @dataProvider defaultRegexes
	 */
	public function testDefaultRegexes(string $path) {
		Assert::same(
			['foo/{name}' => 'a'],
			(new Routing\RegexRoutes(
				new Routing\FakeRoutes(['foo/{name}' => 'a'])
			))->matches(new Uri\FakeUri(null, $path))
		);
		Assert::same(
			['foo/{name} [GET]' => 'a'],
			(new Routing\RegexRoutes(
				new Routing\FakeRoutes(['foo/{name} [GET]' => 'a'])
			))->matches(new Uri\FakeUri(null, $path))
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


(new RegexRoutes())->run();