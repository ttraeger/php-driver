<?php

namespace Cassandra;

class CollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException         InvalidArgumentException
     * @expectedExceptionMessage  Unsupported type 'custom type'
     */
    public function testSupportsOnlyCassandraTypes()
    {
        new Collection('custom type');
    }

    /**
     * @dataProvider cassandraTypes
     */
    public function testSupportsAllCassandraTypes($type)
    {
        new Collection($type);
    }

    /**
     * @dataProvider cassandraTypes
     */
    public function testReturnsItsType($type)
    {
        $list = new Collection($type);
        $this->assertEquals($type, $list->type());
    }

    public function cassandraTypes()
    {
        return array(
            array("ascii"),
            array("bigint"),
            array("blob"),
            array("boolean"),
            array("counter"),
            array("decimal"),
            array("double"),
            array("float"),
            array("int"),
            array("text"),
            array("timestamp"),
            array("uuid"),
            array("varchar"),
            array("varint"),
            array("timeuuid"),
            array("inet"),
        );
    }

    /**
     * @expectedException         InvalidArgumentException
     * @expectedExceptionMessage  Expected an instance of Cassandra\Varint, an instance of Cassandra\Decimal given
     */
    public function testValidatesTypesOfElements()
    {
        $list = new Collection('varint');
        $list->add(new Decimal('123'));
    }

    public function testAddsAllElements()
    {
        $list = new Collection('varint');
        $list->add(new Varint('1'), new Varint('2'), new Varint('3'),
                   new Varint('4'), new Varint('5'), new Varint('6'),
                   new Varint('7'), new Varint('8'));

        $this->assertEquals(8, $list->count());
        $this->assertEquals(
            array(
                new Varint('1'), new Varint('2'), new Varint('3'),
                new Varint('4'), new Varint('5'), new Varint('6'),
                new Varint('7'), new Varint('8')
            ),
            $list->values()
        );
    }

    public function testReturnsNullWhenCannotFindIndex()
    {
        $list = new Collection('varint');
        $this->assertSame(null, $list->find(new Varint('1')));
    }

    public function testFindsIndexOfAnElement()
    {
        $list = new Collection('varint');
        $list->add(new Varint('1'), new Varint('2'), new Varint('3'),
                   new Varint('4'), new Varint('5'), new Varint('6'),
                   new Varint('7'), new Varint('8'));

        $this->assertEquals(0, $list->find(new Varint('1')));
        $this->assertEquals(1, $list->find(new Varint('2')));
        $this->assertEquals(2, $list->find(new Varint('3')));
        $this->assertEquals(3, $list->find(new Varint('4')));
        $this->assertEquals(4, $list->find(new Varint('5')));
        $this->assertEquals(5, $list->find(new Varint('6')));
        $this->assertEquals(6, $list->find(new Varint('7')));
        $this->assertEquals(7, $list->find(new Varint('8')));
    }

    public function testGetsElementByIndex()
    {
        $list = new Collection('varint');
        $list->add(new Varint('1'), new Varint('2'), new Varint('3'),
                   new Varint('4'), new Varint('5'), new Varint('6'),
                   new Varint('7'), new Varint('8'));

        $this->assertEquals(new Varint('1'), $list->get(0));
        $this->assertEquals(new Varint('2'), $list->get(1));
        $this->assertEquals(new Varint('3'), $list->get(2));
        $this->assertEquals(new Varint('4'), $list->get(3));
        $this->assertEquals(new Varint('5'), $list->get(4));
        $this->assertEquals(new Varint('6'), $list->get(5));
        $this->assertEquals(new Varint('7'), $list->get(6));
        $this->assertEquals(new Varint('8'), $list->get(7));
    }
}
