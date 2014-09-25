<?php

require_once __DIR__ . '/src/Pager.php';
require_once __DIR__ . '/src/ButtonPager.php';
require_once __DIR__ . '/src/LinkPager.php';

use mindplay\pager\Pager;
use mindplay\pager\ButtonPager;
use mindplay\pager\LinkPager;

test(
    'Computes meaningful page ranges',
    function () {
        eq(Pager::getPageRanges(1,10,5), array(array(1,2,3,4,5),array(10)), 'left-most at start of short range');
        eq(Pager::getPageRanges(5,10,5), array(array(1,2,3,4,5),array(10)), 'left-most near end of short range');
        eq(Pager::getPageRanges(6,10,5), array(array(1), array(6,7,8,9,10)), 'right-most at start of short range');
        eq(Pager::getPageRanges(1,10,20), array(array(1,2,3,4,5,6,7,8,9,10)), 'range longer than total');
        eq(Pager::getPageRanges(1,100,10), array(array(1,2,3,4,5,6,7,8,9,10),array(100)), 'left-most at start of long range');
        eq(Pager::getPageRanges(50,100,10), array(array(1),array(45,46,47,48,49,50,51,52,53,54,55),array(100)), 'middle of range');
        eq(Pager::getPageRanges(95,100,5), array(array(1),array(93,94,95,96,97),array(100)), 'right-most near end of long range');
        eq(Pager::getPageRanges(96,100,5), array(array(1),array(96,97,98,99,100)), 'right-most within end of long range');
    }
);

test(
    'ButtonPager works nicely',
    function () {
        $pager = new ButtonPager(1, 10);

        $expected = '<button disabled="disabled">&laquo;</button>'
            .'<button name="page" value="1" disabled="disabled" class="is-active">1</button>&nbsp;'
            .'<button name="page" value="2">2</button>&nbsp;'
            .'<button name="page" value="3">3</button>&nbsp;'
            .'<button name="page" value="4">4</button>&nbsp;'
            .'<button name="page" value="5">5</button>&nbsp;'
            .'&#x22EF;&nbsp;'
            .'<button name="page" value="10">10</button>'
            .'<button name="page" value="2">&raquo;</button>';

        eq($pager->render(), $expected, 'yay, buttons!');
    }
);

test(
    'LinkPager is snazzy',
    function () {
        $pager = new LinkPager(1, 3);

        $expected = '<a href="#">&laquo;</a>'
            .'<a href="#1" class="is-active">1</a>&nbsp;'
            .'<a href="#2">2</a>&nbsp;'
            .'<a href="#3">3</a>'
            .'<a href="#2">&raquo;</a>';

        eq($pager->render(), $expected, 'renders links with hash-tags');

        $pager->create_url = function($page) {
            return "result.php?page={$page}";
        };

        $expected = '<a href="#">&laquo;</a>'
            .'<a href="result.php?page=1" class="is-active">1</a>&nbsp;'
            .'<a href="result.php?page=2">2</a>&nbsp;'
            .'<a href="result.php?page=3">3</a>'
            .'<a href="result.php?page=2">&raquo;</a>';

        eq($pager->render(), $expected, 'renders links with URLs');
    }
);

exit(status());

// https://gist.github.com/mindplay-dk/4260582

/**
 * @param string   $name     test description
 * @param callable $function test implementation
 */
function test($name, $function)
{
    echo "\n=== $name ===\n\n";

    try {
        call_user_func($function);
    } catch (Exception $e) {
        ok(false, "UNEXPECTED EXCEPTION", $e);
    }
}

/**
 * @param bool   $result result of assertion
 * @param string $why    description of assertion
 * @param mixed  $value  optional value (displays on failure)
 */
function ok($result, $why = null, $value = null)
{
    if ($result === true) {
        echo "- PASS: " . ($why === null ? 'OK' : $why) . ($value === null ? '' : ' (' . format($value) . ')') . "\n";
    } else {
        echo "# FAIL: " . ($why === null ? 'ERROR' : $why) . ($value === null ? '' : ' - ' . format($value, true)) . "\n";
        status(false);
    }
}

/**
 * @param mixed  $value    value
 * @param mixed  $expected expected value
 * @param string $why      description of assertion
 */
function eq($value, $expected, $why = null)
{
    $result = $value === $expected;

    $info = $result
        ? format($value)
        : "expected: " . format($expected, true) . ", got: " . format($value, true);

    ok($result, ($why === null ? $info : "$why ($info)"));
}

/**
 * @param mixed $value
 * @param bool  $verbose
 *
 * @return string
 */
function format($value, $verbose = false)
{
    if ($value instanceof Exception) {
        return get_class($value)
        . ($verbose ? ": \"" . $value->getMessage() . "\"" : '');
    }

    if (! $verbose && is_array($value)) {
        return 'array[' . count($value) . ']';
    }

    if (is_bool($value)) {
        return $value ? 'TRUE' : 'FALSE';
    }

    if (is_object($value) && !$verbose) {
        return get_class($value);
    }

    return print_r($value, true);
}

/**
 * @param bool|null $status test status
 *
 * @return int number of failures
 */
function status($status = null)
{
    static $failures = 0;

    if ($status === false) {
        $failures += 1;
    }

    return $failures;
}
