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

final class PathMask extends Tester\TestCase {
	public function testNamesByPosition() {
		Assert::same(
			['name' => 'dom', 'position' => 'developer'],
			(new Routing\PathMask(
				'/books/{name}/{position}',
				new Uri\FakeUri(null, '/books/dom/developer', [])
			))->parameters()
		);
	}

	public function testRemovingRegexParts() {
		Assert::same(
			['foo' => '10', 'adjective' => 'cool', 'word' => 'bar'],
			(new Routing\PathMask(
				'/books/{foo \d+}/{adjective \w+}/{word}',
				new Uri\FakeUri(null, '/books/10/cool/bar', [])
			))->parameters()
		);
	}

	public function testPassingOnDifferentNumberOfParameters() {
		Assert::same(
			[],
			(new Routing\PathMask(
				'v1/.+',
				new Uri\FakeUri(null, 'v1/demands/2wrWlWqMg7DY', [])
			))->parameters()
		);
	}
}


(new PathMask())->run();
