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
	public function testMatch() {
		$list = new Ini\Fake(['Foo::render' => '/foo']);
		$routes = new Routing\HttpRoutes($list);
		Assert::same('Foo::render', $routes->match(new Uri\FakeUri(null, '/foo')));
	}

	/**
	 * @throws \UnexpectedValueException HTTP route does not exist
	 */
	public function testStrictTypeMatching() {
		$list = new Ini\Fake(['Foo::render' => true]);
		(new Routing\HttpRoutes($list))->match(new Uri\FakeUri(null, '/foo'));
	}

	public function testStringMatchOnNumber() {
		$list = new Ini\Fake(['5' => '/foo']);
		$routes = new Routing\HttpRoutes($list);
		Assert::same('5', $routes->match(new Uri\FakeUri(null, '/foo')));
	}

	public function testAllowingFalseyMatch() {
		$list = new Ini\Fake(['0' => '/foo']);
		$routes = new Routing\HttpRoutes($list);
		Assert::same('0', $routes->match(new Uri\FakeUri(null, '/foo')));
	}

	/**
	 * @throws \UnexpectedValueException HTTP route does not exist
	 */
	public function testThrowingOnNoMatch() {
		(new Routing\HttpRoutes(new Ini\Fake([])))->match(new Uri\FakeUri(null, ''));
	}

	public function testCaseInsensitiveMatch() {
		$list = new Ini\Fake(['Foo::render' => '/foo', 'Bar::render' => '/BaR']);
		$routes = new Routing\HttpRoutes($list);
		Assert::same('Foo::render', $routes->match(new Uri\FakeUri(null, '/FoO')));
		Assert::same('Bar::render', $routes->match(new Uri\FakeUri(null, '/bar')));
	}

	public function testMultibyteCaseInsensitiveMatch() {
		$list = new Ini\Fake(['Foo::render' => '/foó', 'Bar::render' => '/BaŘ']);
		$routes = new Routing\HttpRoutes($list);
		Assert::same('Foo::render', $routes->match(new Uri\FakeUri(null, '/FoÓ')));
		Assert::same('Bar::render', $routes->match(new Uri\FakeUri(null, '/bař')));
	}

	public function testMultiplePossibilitiesWithFirstMatch() {
		$list = new Ini\Fake(['Foo::render' => '/foo', 'Foo2::render' => '/foo']);
		$routes = new Routing\HttpRoutes($list);
		Assert::same('Foo::render', $routes->match(new Uri\FakeUri(null, '/foo')));
	}
}


(new HttpRoutes())->run();