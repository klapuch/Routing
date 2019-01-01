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

final class QueryMask extends Tester\TestCase {
	public function testExtractingWithoutDefaultParameter() {
		Assert::same(
			['page' => '1'],
			(new Routing\QueryMask(
				'/books?page=1',
				new Uri\FakeUri(null, '/books', [])
			))->parameters()
		);
	}

	public function testExtractingWithDefaultParameter() {
		Assert::same(
			['page' => '1'],
			(new Routing\QueryMask(
				'/books?page=(1)',
				new Uri\FakeUri(null, '/books', [])
			))->parameters()
		);
	}

	public function testNotOverwritingParameterAsAlreadyStated() {
		Assert::same(
			['page' => 2],
			(new Routing\QueryMask(
				'/books?page=(1)',
				new Uri\FakeUri(null, '/books', ['page' => 2])
			))->parameters()
		);
	}

	public function testBracesAsRegularValue() {
		Assert::same(
			['page' => '(1)'],
			(new Routing\QueryMask(
				'/books?page=((1))',
				new Uri\FakeUri(null, '/books', [])
			))->parameters()
		);
	}

	public function testEmptyValueAsDefault() {
		Assert::same(
			['page' => ''],
			(new Routing\QueryMask(
				'/books/?page=()',
				new Uri\FakeUri(null, '/books', [])
			))->parameters()
		);
	}

	public function testRemovingRegexParts() {
		Assert::same(
			['page' => '1'],
			(new Routing\QueryMask(
				'/books?page=(1 \w\d+)',
				new Uri\FakeUri(null, '/books', [])
			))->parameters()
		);
	}

	public function testEmptyValueWithoutRegex() {
		Assert::same(
			['page' => ''],
			(new Routing\QueryMask(
				'/books?page=( \w\d+)',
				new Uri\FakeUri(null, '/books', [])
			))->parameters()
		);
	}
}


(new QueryMask())->run();
