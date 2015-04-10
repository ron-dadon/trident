<?php


class Example_Test extends Trident_Unit_Test
{

    protected function is_10_bigger_than_1()
    {
        return $this->assert_bigger(10,1);
    }

    protected function is_10_smaller_than_1()
    {
        return $this->assert_smaller(10,1);
    }

    protected function is_a_string()
    {
        return $this->assert_is_string('5');
    }

}