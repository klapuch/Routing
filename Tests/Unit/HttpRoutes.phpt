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

final class HttpRoutes extends Tester\TestCase {
	public function testExactMatch() {
		[$destination, $source] = ['Foo/default', '/foo'];
		$routes = new Routing\HttpRoutes([$source => $destination], 'GET');
		$uri = new Uri\FakeUri(null, $source);
		Assert::equal(
			new Routing\HttpRoute($source, $destination, $uri),
			$routes->match($uri)
		);
	}

	/**
	 * @throws \UnexpectedValueException HTTP route for "/foo" does not exist
	 */
	public function testStrictTypeMatching() {
		(new Routing\HttpRoutes(['Foo/default' => true], 'GET'))->match(new Uri\FakeUri(null, '/foo'));
	}

	public function testStringMatchOnNumber() {
		[$destination, $source] = ['5', '/foo'];
		$routes = new Routing\HttpRoutes([$source => $destination], 'GET');
		$uri = new Uri\FakeUri(null, $source);
		Assert::equal(
			new Routing\HttpRoute($source, $destination, $uri),
			$routes->match($uri)
		);
	}

	public function testAllowingFalseyMatch() {
		[$destination, $source] = ['0', '/foo'];
		$routes = new Routing\HttpRoutes([$source => $destination], 'GET');
		$uri = new Uri\FakeUri(null, $source);
		Assert::equal(
			new Routing\HttpRoute($source, $destination, $uri),
			$routes->match($uri)
		);
	}

	/**
	 * @throws \UnexpectedValueException HTTP route for "" does not exist
	 */
	public function testThrowingOnNoMatch() {
		(new Routing\HttpRoutes([], 'GET'))->match(new Uri\FakeUri(null, ''));
	}

	public function testCaseInsensitiveMatch() {
		$routes = new Routing\HttpRoutes(['/foo' => 'Foo/default', '/BaR' => 'Bar/default'], 'GET');
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
		$routes = new Routing\HttpRoutes(['/foó' => 'Foo/default', '/BaŘ' => 'Bar/default'], 'GET');
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

	public function testMultiplePossibilitiesWithLastMatch() {
		[$destination, $source] = ['Bar/default', '/foo'];
		$routes = new Routing\HttpRoutes([$source => 'Foo/default', $source => $destination], 'GET');
		$uri = new Uri\FakeUri(null, $source);
		Assert::equal(
			new Routing\HttpRoute($source, $destination, $uri),
			$routes->match($uri)
		);
	}

	public function testMatchWithSinglePlaceholder() {
		[$destination, $source] = ['Foo/default', '/books/{id}'];
		$routes = new Routing\HttpRoutes([$source => $destination], 'GET');
		$uri = new Uri\FakeUri(null, '/books/1');
		Assert::equal(
			new Routing\HttpRoute($source, $destination, $uri),
			$routes->match($uri)
		);
	}

	/**
	 * @throws \UnexpectedValueException HTTP route for "/books/foo/bar" does not exist
	 */
	public function testThrowingOnPlaceholderAsNestedParameter() {
		[$destination, $source] = ['Foo/default', '/books/{id}'];
		$routes = new Routing\HttpRoutes([$source => $destination], 'GET');
		$routes->match(new Uri\FakeUri(null, '/books/foo/bar'));
	}

	/**
	 * @throws \UnexpectedValueException HTTP route for "blabla/books/foo" does not exist
	 */
	public function testThrowingOnSomePlaceholderMatch() {
		[$destination, $source] = ['Foo/default', '/books/{id}'];
		$routes = new Routing\HttpRoutes([$source => $destination], 'GET');
		$routes->match(new Uri\FakeUri(null, 'blabla/books/foo'));
	}

	/**
	 * @throws \UnexpectedValueException HTTP route for "/books/{id}" does not exist
	 */
	public function testThrowingOnDirectPlaceholderParameter() {
		[$destination, $source] = ['Foo/default', '/books/{id}'];
		$routes = new Routing\HttpRoutes([$source => $destination], 'GET');
		$routes->match(new Uri\FakeUri(null, $source));
	}

	public function testMatchMultipleDifferentPlaceholders() {
		[$destination, $source] = ['Foo/default', '/books/{id}/foo/{key}'];
		$routes = new Routing\HttpRoutes([$source => $destination], 'GET');
		$uri = new Uri\FakeUri(null, '/books/1/foo/nwm');
		Assert::equal(
			new Routing\HttpRoute($source, $destination, $uri),
			$routes->match($uri)
		);
	}

	public function testMatchMultipleDifferentPlaceholdersInRow() {
		[$destination, $source] = ['Foo/default', '/books/{id}/{key}'];
		$routes = new Routing\HttpRoutes([$source => $destination], 'GET');
		$uri = new Uri\FakeUri(null, '/books/1/nwm');
		Assert::equal(
			new Routing\HttpRoute($source, $destination, $uri),
			$routes->match($uri)
		);
	}

	public function testMatchMultipleSamePlaceholdersInRow() {
		[$destination, $source] = ['Foo/default', '/books/{id}/{id}'];
		$routes = new Routing\HttpRoutes([$source => $destination], 'GET');
		$uri = new Uri\FakeUri(null, '/books/1/5');
		Assert::equal(
			new Routing\HttpRoute($source, $destination, $uri),
			$routes->match($uri)
		);
	}

	public function testMatchWithRegex() {
		[$destination, $source] = ['Foo/default', '/books/{id \d}/{key \w+}'];
		$routes = new Routing\HttpRoutes([$source => $destination], 'GET');
		$uri = new Uri\FakeUri(null, '/books/1/bar');
		Assert::equal(
			new Routing\HttpRoute($source, $destination, $uri),
			$routes->match($uri)
		);
	}

	/**
	 * @throws \UnexpectedValueException HTTP route for "/books/10" does not exist
	 */
	public function testThrowingOnNoRegexMatch() {
		[$destination, $source] = ['Foo/default', '/books/{id \d}'];
		$routes = new Routing\HttpRoutes([$source => $destination], 'GET');
		$routes->match(new Uri\FakeUri(null, '/books/10'));
	}

	public function testMatchingWithinExactMethod() {
		[$destination, $source] = ['Foo/default', '/foo [GET]'];
		$routes = new Routing\HttpRoutes([$source => $destination], 'GET');
		$uri = new Uri\FakeUri(null, $source);
		Assert::equal(
			new Routing\HttpRoute($source, $destination, $uri),
			$routes->match($uri)
		);
	}

	public function testMatchingWithinNonConsistentCaseMethod() {
		[$destination, $source] = ['Foo/default', '/foo [gEt]'];
		$routes = new Routing\HttpRoutes([$source => $destination], 'GET');
		$uri = new Uri\FakeUri(null, $source);
		Assert::equal(
			new Routing\HttpRoute($source, $destination, $uri),
			$routes->match($uri)
		);
	}

	/**
	 * @throws \UnexpectedValueException HTTP route for "/foo [PATCH]" does not exist
	 */
	public function testThrowingOnNotMatchingMethod() {
		[$destination, $source] = ['Foo/default', '/foo [PATCH]'];
		$routes = new Routing\HttpRoutes([$source => $destination], 'POST');
		$uri = new Uri\FakeUri(null, $source);
		Assert::equal(
			new Routing\HttpRoute($source, $destination, $uri),
			$routes->match($uri)
		);
	}

	public function testMatchingForEveryMethod() {
		Assert::noError(function() {
			$source = '/foo';
			(new Routing\HttpRoutes(
				[$source => 'Foo/default'],
				'PUT'
			))->match(new Uri\FakeUri(null, $source));
		});
	}
}


(new HttpRoutes())->run();
