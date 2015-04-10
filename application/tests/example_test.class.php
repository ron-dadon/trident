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

    protected function test_objects()
    {
        $u1 = new User_Entity();
        $u2 = new User_Entity();
        $u1->id = 5;
        $u1->e_mail = 'a';
        $u1->name = 'b';
        $u2->id = 5;
        $u2->e_mail = 'c';
        $u2->name = 'b';
        return $this->assert_equal($u1,$u2);
    }

    protected function test_arrays()
    {
        $u1 = ['a','b', 'c' => 'd'];
        $u2 = ['b', 'z', 'c' => 'd'];
        return $this->assert_equal($u1,$u2);
    }

}