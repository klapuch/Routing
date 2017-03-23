<?php
declare(strict_types = 1);
/**
 * @testCase
 * @phpVersion > 7.1
 */
namespace Klapuch\Routing\Unit;

use Klapuch\Ini;
use Klapuch\Routing;
use Klapuch\Uri;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

final class HttpRoutes extends Tester\TestCase {
	public function testExactMatch() {
		$routes = new Routing\HttpRoutes(new Ini\Fake(['Foo/bar' => '/foo']));
		Assert::same('Foo/bar', $routes->match(new Uri\FakeUri(null, '/foo')));
	}

	/**
	 * @throws \UnexpectedValueException HTTP route does not exist
	 */
	public function testStrictTypeMatching() {
		$list = new Ini\Fake(['Foo/bar' => true]);
		(new Routing\HttpRoutes($list))->match(new Uri\FakeUri(null, '/foo'));
	}

	public function testStringMatchOnNumber() {
		$routes = new Routing\HttpRoutes(new Ini\Fake(['5' => '/foo']));
		Assert::same('5', $routes->match(new Uri\FakeUri(null, '/foo')));
	}

	public function testAllowingFalseyMatch() {
		$routes = new Routing\HttpRoutes(new Ini\Fake(['0' => '/foo']));
		Assert::same('0', $routes->match(new Uri\FakeUri(null, '/foo')));
	}

	/**
	 * @throws \UnexpectedValueException HTTP route does not exist
	 */
	public function testThrowingOnNoMatch() {
		(new Routing\HttpRoutes(new Ini\Fake([])))->match(new Uri\FakeUri(null, ''));
	}

	public function testCaseInsensitiveMatch() {
		$list = new Ini\Fake(['Foo/bar' => '/foo', 'Bar::render' => '/BaR']);
		$routes = new Routing\HttpRoutes($list);
		Assert::same('Foo/bar', $routes->match(new Uri\FakeUri(null, '/FoO')));
		Assert::same('Bar::render', $routes->match(new Uri\FakeUri(null, '/bar')));
	}

	public function testMultibyteCaseInsensitiveMatch() {
		$list = new Ini\Fake(['Foo/bar' => '/foó', 'Bar::render' => '/BaŘ']);
		$routes = new Routing\HttpRoutes($list);
		Assert::same('Foo/bar', $routes->match(new Uri\FakeUri(null, '/FoÓ')));
		Assert::same('Bar::render', $routes->match(new Uri\FakeUri(null, '/bař')));
	}

	public function testMultiplePossibilitiesWithFirstMatch() {
		$list = new Ini\Fake(['Foo/bar' => '/foo', 'Foo2::render' => '/foo']);
		$routes = new Routing\HttpRoutes($list);
		Assert::same('Foo/bar', $routes->match(new Uri\FakeUri(null, '/foo')));
	}

	public function testMatchWithSinglePlaceholder() {
		$list = new Ini\Fake(['Foo/bar' => '/books/{id}']);
		$routes = new Routing\HttpRoutes($list);
		Assert::same(
			'Foo/bar',
			$routes->match(new Uri\FakeUri(null, '/books/1'))
		);
	}

	/**
	 * @throws \UnexpectedValueException HTTP route does not exist
	 */
	public function testThrowingOnPlaceholderAsNestedParameter() {
		$list = new Ini\Fake(['Foo/bar' => '/books/{id}']);
		$routes = new Routing\HttpRoutes($list);
		$routes->match(new Uri\FakeUri(null, '/books/foo/bar'));
	}

	/**
	 * @throws \UnexpectedValueException HTTP route does not exist
	 */
	public function testThrowingOnSomePlaceholderMatch() {
		$list = new Ini\Fake(['Foo/bar' => '/books/{id}']);
		$routes = new Routing\HttpRoutes($list);
		$routes->match(new Uri\FakeUri(null, 'blabla/books/foo'));
	}

	/**
	 * @throws \UnexpectedValueException HTTP route does not exist
	 */
	public function testThrowingOnDirectPlaceholderParameter() {
		$list = new Ini\Fake(['Foo/bar' => '/books/{id}']);
		$routes = new Routing\HttpRoutes($list);
		$routes->match(new Uri\FakeUri(null, '/books/{id}'));
	}

	public function testMatchMultipleDifferentPlaceholdersInRow() {
		$list = new Ini\Fake(['Foo/bar' => '/books/{id}/{key}']);
		$routes = new Routing\HttpRoutes($list);
		Assert::same(
			'Foo/bar',
			$routes->match(new Uri\FakeUri(null, '/books/1/nwm'))
		);
	}

	public function testMatchMultipleSamePlaceholdersInRow() {
		$list = new Ini\Fake(['Foo/bar' => '/books/{id}/{id}']);
		$routes = new Routing\HttpRoutes($list);
		Assert::same(
			'Foo/bar',
			$routes->match(new Uri\FakeUri(null, '/books/1/5'))
		);
	}
}


(new HttpRoutes())->run();