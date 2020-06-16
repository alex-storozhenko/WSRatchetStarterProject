<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class ArrayConnectionRepositoryTest extends TestCase
{
    public function testCannotBeComparedWithInvalidOperator(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $arrConnectionRepository = new \App\Repositories\ArrayConnectionRepository();
        $arrConnectionRepository->whereResourceId(0, '*');
    }
}
