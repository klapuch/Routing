<?php
declare(strict_types = 1);
namespace Klapuch\Routing;

interface Route {
	/**
	 * The resource e.g. cars, books, people
	 * @return string
	 */
	public function resource(): string;

	/**
	 * The action e.g. buy, read, chat
	 * @return string
	 */
	public function action(): string;

	/**
	 * The parameters e.g. [1, read]
	 * @return array
	 */
	public function parameters(): array;
}