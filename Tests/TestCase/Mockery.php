<?php
declare(strict_types = 1);

namespace Klapuch\Routing\TestCase;

use Mockery\LegacyMockInterface;
use Tester;

abstract class Mockery extends Tester\TestCase {
	final protected function mock(string $class): LegacyMockInterface {
		return \Mockery::mock($class);
	}

	protected function tearDown(): void {
		parent::tearDown();
		\Mockery::close();
	}
}
