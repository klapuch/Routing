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

final class HttpRoute extends Tester\TestCase {
	public function testExtractingResourceName() {
		Assert::same(
			'Foo',
			(new Routing\HttpRoute(
				'',
				'Foo/bar',
				new Uri\FakeUri()
			))->resource()
		);
	}

	public function testExtractingActionName() {
		Assert::same(
			'bar',
			(new Routing\HttpRoute(
				'',
				'Foo/bar',
				new Uri\FakeUri()
			))->action()
		);
	}

	public function testExtractingMatchedParameters() {
		Assert::same(
			['foo' => 'foo', 'adjective' => 'cool'],
			(new Routing\HttpRoute(
				'/books/{foo}/{adjective}',
				'Foo/bar',
				new Uri\FakeUri(null, '/books/foo/cool')
			))->parameters()
		);
	}

	public function testTypeAwareParameters() {
		Assert::same(
			['a' => 1, 'b' => 123456789, 'c' => '1ab', 'd' => 'ba1'],
			(new Routing\HttpRoute(
				'/books/{a}/{b}/{c}/{d}',
				'Foo/bar',
				new Uri\FakeUri(null, '/books/1/123456789/1ab/ba1')
			))->parameters()
		);
	}

	public function testRemovingRegexParts() {
		Assert::same(
			['foo' => 10, 'adjective' => 'cool', 'word' => 'bar'],
			(new Routing\HttpRoute(
				'/books/{foo \d+}/{adjective \w+}/{word}',
				'Foo/bar',
				new Uri\FakeUri(null, '/books/10/cool/bar')
			))->parameters()
		);
	}
}


(new HttpRoute())->run();