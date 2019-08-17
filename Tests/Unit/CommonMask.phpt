<?php
declare(strict_types = 1);

/**
 * @testCase
 * @phpVersion > 7.1
 */

namespace Klapuch\Routing\Unit;

use Klapuch\Routing;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

final class CommonMask extends Tester\TestCase {
	public function testDefaults() {
		Assert::same(
			['sort' => '', 'page' => 1, 'per_page' => 10, 'fields' => ''],
			(new Routing\CommonMask())->parameters()
		);
	}

	public function testMinRequirements() {
		$_GET['page'] = 0;
		$_GET['per_page'] = 0;
		Assert::same(
			['sort' => '', 'page' => 1, 'per_page' => 10, 'fields' => ''],
			(new Routing\CommonMask())->parameters()
		);
	}

	public function testAllowingBelowRequirements() {
		$_GET['page'] = 1;
		$_GET['per_page'] = 9;
		Assert::same(
			['sort' => '', 'page' => 1, 'per_page' => 9, 'fields' => ''],
			(new Routing\CommonMask())->parameters()
		);
	}

	public function testAlwaysInt() {
		$_GET['page'] = '5';
		$_GET['per_page'] = '20';
		Assert::same(
			['sort' => '', 'page' => 5, 'per_page' => 20, 'fields' => ''],
			(new Routing\CommonMask())->parameters()
		);
	}

	public function testInvalidIntLeadingToDefaults() {
		$_GET['page'] = 'a';
		$_GET['per_page'] = 'b';
		Assert::same(
			['sort' => '', 'page' => 1, 'per_page' => 10, 'fields' => ''],
			(new Routing\CommonMask())->parameters()
		);
	}
}


(new CommonMask())->run();
