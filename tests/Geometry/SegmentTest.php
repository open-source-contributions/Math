<?php
declare(strict_types = 1);

namespace Tests\Innmind\Math\Geometry;

use Innmind\Math\{
    Geometry\Segment,
    Geometry\Angle,
    Geometry\Angle\Degree,
    Algebra\Integer,
    Exception\LengthMustBePositive
};
use PHPUnit\Framework\TestCase;

class SegmentTest extends TestCase
{
    public function testLength()
    {
        $segment = new Segment($length = new Integer(2));

        $this->assertSame($length, $segment->length());
    }

    public function testStringCast()
    {
        $this->assertSame('2', (new Segment(new Integer(2)))->toString());
    }

    public function testThrowWhenNullSegment()
    {
        $this->expectException(LengthMustBePositive::class);

        new Segment(new Integer(0));
    }

    public function testThrowWhenNegativeLength()
    {
        $this->expectException(LengthMustBePositive::class);

        new Segment(new Integer(-1));
    }

    public function testJoin()
    {
        $first = new Segment(new Integer(1));
        $second = new Segment(new Integer(1));
        $degree = new Degree(new Integer(42));

        $angle = $first->join($second, $degree);

        $this->assertInstanceOf(Angle::class, $angle);
        $this->assertSame($first, $angle->firstSegment());
        $this->assertSame($degree, $angle->degree());
        $this->assertSame($second, $angle->secondSegment());
    }
}
