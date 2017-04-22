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
		[$destination, $source] = ['Foo/default', '/foo'];
		$routes = new Routing\HttpRoutes(new Ini\FakeSource([$destination => $source]));
		$uri = new Uri\FakeUri(null, $source);
		Assert::equal(
			new Routing\HttpRoute($source, $destination, $uri),
			$routes->match($uri)
		);
	}

	/**
	 * @throws \UnexpectedValueException HTTP route does not exist
	 */
	public function testStrictTypeMatching() {
		$list = new Ini\FakeSource(['Foo/default' => true]);
		(new Routing\HttpRoutes($list))->match(new Uri\FakeUri(null, '/foo'));
	}

	public function testStringMatchOnNumber() {
		[$destination, $source] = ['5', '/foo'];
		$routes = new Routing\HttpRoutes(new Ini\FakeSource([$destination => $source]));
		$uri = new Uri\FakeUri(null, $source);
		Assert::equal(
			new Routing\HttpRoute($source, $destination, $uri),
			$routes->match($uri)
		);
	}

	public function testAllowingFalseyMatch() {
		[$destination, $source] = ['0', '/foo'];
		$routes = new Routing\HttpRoutes(new Ini\FakeSource([$destination => $source]));
		$uri = new Uri\FakeUri(null, $source);
		Assert::equal(
			new Routing\HttpRoute($source, $destination, $uri),
			$routes->match($uri)
		);
	}

	/**
	 * @throws \UnexpectedValueException HTTP route does not exist
	 */
	public function testThrowingOnNoMatch() {
		(new Routing\HttpRoutes(new Ini\FakeSource([])))->match(new Uri\FakeUri(null, ''));
	}

	public function testCaseInsensitiveMatch() {
		$ini = new Ini\FakeSource(['Foo/default' => '/foo', 'Bar/default' => '/BaR']);
		$routes = new Routing\HttpRoutes($ini);
		$fooUri = new Uri\FakeUri(null, '/foo');
		$barUri = new Uri\FakeUri(null, '/BaR');
		Assert::equal(
			new Routing\HttpRoute('/foo', 'Foo/default', $fooUri),
			$routes->match($fooUri)
		);
		Assert::equal(
			new Routing\HttpRoute('/BaR', 'Bar/default', $barUri),
			$routes->match($barUri)
		);
	}

	public function testMultibyteCaseInsensitiveMatch() {
		$ini = new Ini\FakeSource(['Foo/default' => '/foó', 'Bar/default' => '/BaŘ']);
		$routes = new Routing\HttpRoutes($ini);
		$fooUri = new Uri\FakeUri(null, '/foó');
		$barUri = new Uri\FakeUri(null, '/BaŘ');
		Assert::equal(
			new Routing\HttpRoute('/foó', 'Foo/default', $fooUri),
			$routes->match($fooUri)
		);
		Assert::equal(
			new Routing\HttpRoute('/BaŘ', 'Bar/default', $barUri),
			$routes->match($barUri)
		);
	}

	public function testMultiplePossibilitiesWithFirstMatch() {
		[$destination, $source] = ['Foo/default', '/foo'];
		$ini = new Ini\FakeSource([$destination => $source, 'Bar/default' => $source]);
		$routes = new Routing\HttpRoutes($ini);
		$uri = new Uri\FakeUri(null, $source);
		Assert::equal(
			new Routing\HttpRoute($source, $destination, $uri),
			$routes->match($uri)
		);
	}

	public function testMatchWithSinglePlaceholder() {
		[$destination, $source] = ['Foo/default', '/books/{id}'];
		$ini = new Ini\FakeSource([$destination => $source]);
		$routes = new Routing\HttpRoutes($ini);
		$uri = new Uri\FakeUri(null, '/books/1');
		Assert::equal(
			new Routing\HttpRoute($source, $destination, $uri),
			$routes->match($uri)
		);
	}

	/**
	 * @throws \UnexpectedValueException HTTP route does not exist
	 */
	public function testThrowingOnPlaceholderAsNestedParameter() {
		[$destination, $source] = ['Foo/default', '/books/{id}'];
		$ini = new Ini\FakeSource([$destination => $source]);
		$routes = new Routing\HttpRoutes($ini);
		$routes->match(new Uri\FakeUri(null, '/books/foo/bar'));
	}

	/**
	 * @throws \UnexpectedValueException HTTP route does not exist
	 */
	public function testThrowingOnSomePlaceholderMatch() {
		[$destination, $source] = ['Foo/default', '/books/{id}'];
		$ini = new Ini\FakeSource([$destination => $source]);
		$routes = new Routing\HttpRoutes($ini);
		$routes->match(new Uri\FakeUri(null, 'blabla/books/foo'));
	}

	/**
	 * @throws \UnexpectedValueException HTTP route does not exist
	 */
	public function testThrowingOnDirectPlaceholderParameter() {
		[$destination, $source] = ['Foo/default', '/books/{id}'];
		$ini = new Ini\FakeSource([$destination => $source]);
		$routes = new Routing\HttpRoutes($ini);
		$routes->match(new Uri\FakeUri(null, $source));
	}

	public function testMatchMultipleDifferentPlaceholders() {
		[$destination, $source] = ['Foo/default', '/books/{id}/foo/{key}'];
		$ini = new Ini\FakeSource([$destination => $source]);
		$routes = new Routing\HttpRoutes($ini);
		$uri = new Uri\FakeUri(null, '/books/1/foo/nwm');
		Assert::equal(
			new Routing\HttpRoute($source, $destination, $uri),
			$routes->match($uri)
		);
	}

	public function testMatchMultipleDifferentPlaceholdersInRow() {
		[$destination, $source] = ['Foo/default', '/books/{id}/{key}'];
		$ini = new Ini\FakeSource([$destination => $source]);
		$routes = new Routing\HttpRoutes($ini);
		$uri = new Uri\FakeUri(null, '/books/1/nwm');
		Assert::equal(
			new Routing\HttpRoute($source, $destination, $uri),
			$routes->match($uri)
		);
	}

	public function testMatchMultipleSamePlaceholdersInRow() {
		[$destination, $source] = ['Foo/default', '/books/{id}/{id}'];
		$ini = new Ini\FakeSource([$destination => $source]);
		$routes = new Routing\HttpRoutes($ini);
		$uri = new Uri\FakeUri(null, '/books/1/5');
		Assert::equal(
			new Routing\HttpRoute($source, $destination , $uri),
			$routes->match($uri)
		);
	}

	public function testMatchWithRegex() {
		[$destination, $source] = ['Foo/default', '/books/{id \d}/{key \w+}'];
		$ini = new Ini\FakeSource([$destination => $source]);
		$routes = new Routing\HttpRoutes($ini);
		$uri = new Uri\FakeUri(null, '/books/1/bar');
		Assert::equal(
			new Routing\HttpRoute($source, $destination, $uri),
			$routes->match($uri)
		);
	}

	/**
	 * @throws \UnexpectedValueException HTTP route does not exist
	 */
	public function testThrowingOnNoRegexMatch() {
		[$destination, $source] = ['Foo/default', '/books/{id \d}'];
		$ini = new Ini\FakeSource([$destination => $source]);
		$routes = new Routing\HttpRoutes($ini);
		$routes->match(new Uri\FakeUri(null, '/books/10'));
	}
}


(new HttpRoutes())->run();