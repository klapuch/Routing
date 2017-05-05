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

final class MappedRoute extends Tester\TestCase {
	public function testAbsoluteNamespace() {
		Assert::same(
			'\App\Page\Sign\In',
			(new Routing\MappedRoute(
				new Routing\FakeRoute('Sign', 'In'),
				'App\Page'
			))->resource()
		);
	}

	public function testIgnoringLeadingAndTrailingNamespaceSeparator() {
		Assert::same(
			'\App\Page\Sign\In',
			(new Routing\MappedRoute(
				new Routing\FakeRoute('Sign', 'in'),
				'\App\Page\\'
			))->resource()
		);
	}

	public function testClassWithLeadingUpperCasesUppersCase() {
		Assert::same(
			'\App\Page\Sign\In',
			(new Routing\MappedRoute(
				new Routing\FakeRoute('sign', 'in'),
				'App\Page'
			))->resource()
		);
	}

	public function testAddingClassSuffix() {
		Assert::same(
			'\App\Page\Sign\InPage',
			(new Routing\MappedRoute(
				new Routing\FakeRoute('sign', 'in'),
				'App\Page',
				'Page'
			))->resource()
		);
	}
}


(new MappedRoute())->run();