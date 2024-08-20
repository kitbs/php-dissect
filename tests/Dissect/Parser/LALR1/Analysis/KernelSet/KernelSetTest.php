<?php

namespace Dissect\Parser\LALR1\Analysis\KernelSet;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class KernelSetTest extends TestCase
{
    #[Test]
    public function kernelsShouldBeProperlyHashedAndOrdered()
    {
        $this->assertEquals([1, 3, 6, 7], KernelSet::hashKernel([
            [2, 1],
            [1, 0],
            [2, 0],
            [3, 0],
        ]));
    }

    #[Test]
    public function insertShouldInsertANewNodeIfNoIdenticalKernelExists()
    {
        $set = new KernelSet;

        $this->assertEquals(0, $set->insert([
            [2, 1],
        ]));

        $this->assertEquals(1, $set->insert([
            [2, 2],
        ]));

        $this->assertEquals(2, $set->insert([
            [1, 1],
        ]));

        $this->assertEquals(0, $set->insert([
            [2, 1],
        ]));
    }
}
