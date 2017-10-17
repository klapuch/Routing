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

final class DefaultRoute extends Tester\TestCase {
	public function testExtractingResourceName() {
		Assert::same(
			'Foo',
			(new Routing\DefaultRoute('', 'Foo/bar', new Uri\FakeUri()))->resource()
		);
	}

	public function testExtractingActionName() {
		Assert::same(
			'bar',
			(new Routing\DefaultRoute('', 'Foo/bar', new Uri\FakeUri()))->action()
		);
	}

	public function testNoAvailableActionWithEmptyOutcome() {
		Assert::same(
			'',
			(new Routing\DefaultRoute('foo/', 'Foo', new Uri\FakeUri()))->action()
		);
	}

	public function testExtractingParametersByPosition() {
		Assert::same(
			['name' => 'dom', 'position' => 'developer'],
			(new Routing\DefaultRoute(
				'/books/{name}/{position}',
				'Foo/bar',
				new Uri\FakeUri(null, '/books/dom/developer')
			))->parameters()
		);
	}

	public function testExtractingWithQuery() {
		Assert::same(
			['page' => '1', 'name' => 'dom', 'position' => 'developer'],
			(new Routing\DefaultRoute(
				'/books/{name}/{position}?page=1',
				'Foo/bar',
				new Uri\FakeUri(null, '/books/dom/developer')
			))->parameters()
		);
	}

	public function testPrecedenceToParameters() {
		Assert::same(
			['page' => '1', 'position' => 'developer'],
			(new Routing\DefaultRoute(
				'/books/{page}/{position}?page=1',
				'Foo/bar',
				new Uri\FakeUri(null, '/books/2/developer')
			))->parameters()
		);
	}

	public function testRemovingRegexParts() {
		Assert::same(
			['foo' => '10', 'adjective' => 'cool', 'word' => 'bar'],
			(new Routing\DefaultRoute(
				'/books/{foo \d+}/{adjective \w+}/{word}',
				'Foo/bar',
				new Uri\FakeUri(null, '/books/10/cool/bar')
			))->parameters()
		);
	}
}


(new DefaultRoute())->run();