<?php
/**
 * Trident Framework - PHP MVC Framework
 *
 * The MIT License (MIT)
 * Copyright (c) 2015 Ron Dadon
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * Class Trident_Unit_Test (Experimental)
 *
 * Base class for unit tests.
 */
abstract class Trident_Unit_Test
{

    function __construct()
    {
        assert_options(ASSERT_ACTIVE, 1);
        assert_options(ASSERT_WARNING, 0);
        assert_options(ASSERT_QUIET_EVAL, 1);
    }

    private function assert($test, $actual, $expected, $statement, $show = false)
    {
        if ($result = assert($statement))
        {
            echo "<tr><td>$test</td><td colspan=\"3\"><span class=\"label label-success\">Success</span></td></tr>";

        }
        else
        {
            if ($show)
            {
                if (is_object($actual) || is_array($actual))
                {
                    $actual = print_r($actual, true);
                }
                else
                {
                    $actual = htmlspecialchars($actual, ENT_HTML5 | ENT_QUOTES);
                }
                if (is_object($expected) || is_array($expected))
                {
                    $expected = print_r($expected, true);
                }
                else
                {
                    $expected = htmlspecialchars($expected, ENT_HTML5 | ENT_QUOTES);
                }
                echo "<tr><td>$test</td><td><span class=\"label label-danger\">Fail</span></td>
                        <td>$actual</td><td>$expected</td></tr>";
            }
            else
            {
                echo "<tr><td>$test</td><td colspan=\"3\"><span class=\"label label-danger\">Fail</span></td></tr>";
            }
        }
        return $result;

    }

    protected function assert_equal($actual, $expected)
    {
        $test = debug_backtrace()[1]['function'];
        $test = ucfirst(str_replace('_', ' ', $test));
        if (is_object($actual) && is_object($expected))
        {
            return $this->assert($test, $actual, $expected, $actual == $expected, true);
        }
        if (is_array($actual) && is_array($expected))
        {
            return $this->assert($test, $actual, $expected,
                count(array_diff($actual, $expected)) === 0 && count(array_diff($expected, $actual)) === 0 &&
                count(array_diff_key($actual, $expected)) === 0 && count(array_diff_key($expected, $actual)) === 0,
                true);
        }
        return $this->assert($test, $actual, $expected, $actual === $expected, true);
    }

    protected function assert_not_equal($actual, $expected)
    {
        $test = debug_backtrace()[1]['function'];
        $test = ucfirst(str_replace('_', ' ', $test));
        if (is_object($actual) && is_object($expected))
        {
            return $this->assert($test, $actual, $expected, $actual != $expected, true);
        }
        if (is_array($actual) && is_array($expected))
        {
            return $this->assert($test, $actual, $expected,
                count(array_diff($actual, $expected)) !== 0 || count(array_diff($expected, $actual)) !== 0 ||
                count(array_diff_key($actual, $expected)) !== 0 || count(array_diff_key($expected, $actual)) !== 0,
                true);
        }
        return $this->assert($test, $actual, $expected, $actual !== $expected);
    }

    protected function assert_bigger($value_1, $value_2)
    {
        $test = debug_backtrace()[1]['function'];
        $test = ucfirst(str_replace('_', ' ', $test));
        return $this->assert($test, $value_1, $value_2, $value_1 > $value_2);
    }

    protected function assert_smaller($value_1, $value_2)
    {
        $test = debug_backtrace()[1]['function'];
        $test = ucfirst(str_replace('_', ' ', $test));
        return $this->assert($test, $value_1, $value_2, $value_1 < $value_2);
    }

    protected function assert_is_number($value)
    {
        $test = debug_backtrace()[1]['function'];
        $test = ucfirst(str_replace('_', ' ', $test));
        return $this->assert($test, $value, '', is_numeric($value));
    }

    protected function assert_is_string($value)
    {
        $test = debug_backtrace()[1]['function'];
        $test = ucfirst(str_replace('_', ' ', $test));
        return $this->assert($test, $value, '', is_string($value));
    }

    protected function assert_is_array($value)
    {
        $test = debug_backtrace()[1]['function'];
        $test = ucfirst(str_replace('_', ' ', $test));
        return $this->assert($test, $value, '', is_array($value));
    }

    public function run_test()
    {
        $tests = array_diff(get_class_methods(get_called_class()), get_class_methods('Trident_Unit_Test'));
        if (count($tests) === 0)
        {
            die('No tests to run.');
        }
        $template_header = file_get_contents(dirname(__FILE__) . DS . 'trident_test_template_header.php');
        $template_footer = file_get_contents(dirname(__FILE__) . DS . 'trident_test_template_footer.php');
        $success = 0; $fail = 0;
        $template_header = str_replace('{test-name}', str_replace('_', ' ', get_called_class()), $template_header);
        echo $template_header;
        foreach ($tests as $test)
        {
            if ($this->$test())
            {
                $success++;
            }
            else
            {
                $fail++;
            }
        }
        $template_footer = str_replace('{tests-number}', count($tests), $template_footer);
        $template_footer = str_replace('{success-number}', $success, $template_footer);
        $template_footer = str_replace('{fail-number}', $fail, $template_footer);
        echo $template_footer;
    }

}