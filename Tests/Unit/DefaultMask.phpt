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

final class DefaultMask extends Tester\TestCase {
	public function testExtractingParametersByPosition() {
		Assert::same(
			['name' => 'dom', 'position' => 'developer'],
			(new Routing\DefaultMask(
				'/books/{name}/{position}',
				new Uri\FakeUri(null, '/books/dom/developer', [])
			))->parameters()
		);
	}

	public function testExtractingWithoutDefaultQuery() {
		Assert::same(
			['page' => '1', 'name' => 'dom', 'position' => 'developer'],
			(new Routing\DefaultMask(
				'/books/{name}/{position}?page=1',
				new Uri\FakeUri(null, '/books/dom/developer', [])
			))->parameters()
		);
	}

	public function testExtractingWithDefaultQuery() {
		Assert::same(
			['page' => '1', 'name' => 'dom', 'position' => 'developer'],
			(new Routing\DefaultMask(
				'/books/{name}/{position}?page=(1)',
				new Uri\FakeUri(null, '/books/dom/developer', [])
			))->parameters()
		);
	}

	public function testNotOverwritingQueryAsAlreadyStated() {
		Assert::same(
			['page' => 2, 'name' => 'dom', 'position' => 'developer'],
			(new Routing\DefaultMask(
				'/books/{name}/{position}?page=(1)',
				new Uri\FakeUri(null, '/books/dom/developer', ['page' => 2])
			))->parameters()
		);
	}

	public function testBracesAsRegularValue() {
		Assert::same(
			['page' => '(1)', 'name' => 'dom', 'position' => 'developer'],
			(new Routing\DefaultMask(
				'/books/{name}/{position}?page=((1))',
				new Uri\FakeUri(null, '/books/dom/developer', [])
			))->parameters()
		);
	}

	public function testEmptyValueAsDefault() {
		Assert::same(
			['page' => ''],
			(new Routing\DefaultMask(
				'/books/?page=()',
				new Uri\FakeUri(null, '/books/', [])
			))->parameters()
		);
	}

	public function testPrecedenceToParameters() {
		Assert::same(
			['page' => '1', 'position' => 'developer'],
			(new Routing\DefaultMask(
				'/books/{page}/{position}?page=1',
				new Uri\FakeUri(null, '/books/2/developer', [])
			))->parameters()
		);
	}

	public function testRemovingRegexParts() {
		Assert::same(
			['foo' => '10', 'adjective' => 'cool', 'word' => 'bar'],
			(new Routing\DefaultMask(
				'/books/{foo \d+}/{adjective \w+}/{word}',
				new Uri\FakeUri(null, '/books/10/cool/bar', [])
			))->parameters()
		);
	}

	public function testExtractingDefaultQueryWithoutRegex() {
		Assert::same(
			['page' => '1', 'name' => 'dom', 'position' => 'developer'],
			(new Routing\DefaultMask(
				'/books/{name}/{position}?page=(1 \w\d+)',
				new Uri\FakeUri(null, '/books/dom/developer', [])
			))->parameters()
		);
	}

	public function testExtractingDefaultEmptyValueQueryWithoutRegex() {
		Assert::same(
			['page' => '', 'name' => 'dom', 'position' => 'developer'],
			(new Routing\DefaultMask(
				'/books/{name}/{position}?page=( \w\d+)',
				new Uri\FakeUri(null, '/books/dom/developer', [])
			))->parameters()
		);
	}
}


(new DefaultMask())->run();