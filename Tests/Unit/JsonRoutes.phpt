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

final class JsonRoutes extends Tester\TestCase {
	public function testLoadingExistingFile() {
		Assert::same(
			[
				'a' => 'b',
				'c' => 'd',
			],
			(new Routing\JsonRoutes(
				new \SplFileInfo(
					Tester\FileMock::create(
						json_encode(
							[
								'a' => 'b',
								'c' => 'd',
							]
						)
					)
				)
			))->matches()
		);
	}

	/**
	 * @throws \UnexpectedValueException Routes in JSON as /foo/bar.json does not exist
	 */
	public function testThrowingOnLoadingUnknownFile() {
		(new Routing\JsonRoutes(
			new \SplFileInfo('/foo/bar.json')
		))->matches();
	}
}


(new JsonRoutes())->run();
