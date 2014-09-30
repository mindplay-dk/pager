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
        expectRanges(1, 1, 5, '[1]');

        expectRanges(1, 2, 5, '[1] 2');
        expectRanges(2, 2, 5, '1 [2]');

        expectRanges(1, 3, 5, '[1] 2 3');
        expectRanges(2, 3, 5, '1 [2] 3');
        expectRanges(3, 3, 5, '1 2 [3]');

        expectRanges(1, 5, 5, '[1] 2 3 4 5');
        expectRanges(2, 5, 5, '1 [2] 3 4 5');
        expectRanges(3, 5, 5, '1 2 [3] 4 5');
        expectRanges(4, 5, 5, '1 2 3 [4] 5');
        expectRanges(5, 5, 5, '1 2 3 4 [5]');

        expectRanges(1, 8, 5, '[1] 2 3 4 5 6 7 8');
        expectRanges(2, 8, 5, '1 [2] 3 4 5 6 7 8');
        expectRanges(3, 8, 5, '1 2 [3] 4 5 6 7 8');
        expectRanges(4, 8, 5, '1 2 3 [4] 5 6 7 8');
        expectRanges(5, 8, 5, '1 2 3 4 [5] 6 7 8');
        expectRanges(6, 8, 5, '1 2 3 4 5 [6] 7 8');
        expectRanges(7, 8, 5, '1 2 3 4 5 6 [7] 8');
        expectRanges(8, 8, 5, '1 2 3 4 5 6 7 [8]');

        expectRanges(1, 9, 5, '[1] 2 3 4 5 6 7 8 9');
        expectRanges(2, 9, 5, '1 [2] 3 4 5 6 7 8 9');
        expectRanges(3, 9, 5, '1 2 [3] 4 5 6 7 8 9');
        expectRanges(4, 9, 5, '1 2 3 [4] 5 6 7 8 9');
        expectRanges(5, 9, 5, '1 2 3 4 [5] 6 7 8 9');
        expectRanges(6, 9, 5, '1 2 3 4 5 [6] 7 8 9');
        expectRanges(7, 9, 5, '1 2 3 4 5 6 [7] 8 9');
        expectRanges(8, 9, 5, '1 2 3 4 5 6 7 [8] 9');
        expectRanges(9, 9, 5, '1 2 3 4 5 6 7 8 [9]');

        expectRanges(1, 10, 5, '[1] 2 3 4 5 .. 10');
        expectRanges(2, 10, 5, '1 [2] 3 4 5 .. 10');
        expectRanges(3, 10, 5, '1 2 [3] 4 5 .. 10');
        expectRanges(4, 10, 5, '1 2 3 [4] 5 6 .. 10');
        expectRanges(5, 10, 5, '1 2 3 4 [5] 6 7 .. 10');
        expectRanges(6, 10, 5, '1 .. 4 5 [6] 7 8 9 10');
        expectRanges(7, 10, 5, '1 .. 5 6 [7] 8 9 10');
        expectRanges(8, 10, 5, '1 .. 5 6 7 [8] 9 10');
        expectRanges(9, 10, 5, '1 .. 5 6 7 8 [9] 10');
        expectRanges(10, 10, 5, '1 .. 5 6 7 8 9 [10]');

        expectRanges(1, 11, 5, '[1] 2 3 4 5 .. 11');
        expectRanges(2, 11, 5, '1 [2] 3 4 5 .. 11');
        expectRanges(3, 11, 5, '1 2 [3] 4 5 .. 11');
        expectRanges(4, 11, 5, '1 2 3 [4] 5 6 .. 11');
        expectRanges(5, 11, 5, '1 2 3 4 [5] 6 7 .. 11');
        expectRanges(6, 11, 5, '1 .. 4 5 [6] 7 8 .. 11');
        expectRanges(7, 11, 5, '1 .. 5 6 [7] 8 9 10 11');
        expectRanges(8, 11, 5, '1 .. 6 7 [8] 9 10 11');
        expectRanges(9, 11, 5, '1 .. 6 7 8 [9] 10 11');
        expectRanges(10, 11, 5, '1 .. 6 7 8 9 [10] 11');
        expectRanges(11, 11, 5, '1 .. 6 7 8 9 10 [11]');

        expectRanges(1, 12, 5, '[1] 2 3 4 5 .. 12');
        expectRanges(2, 12, 5, '1 [2] 3 4 5 .. 12');
        expectRanges(3, 12, 5, '1 2 [3] 4 5 .. 12');
        expectRanges(4, 12, 5, '1 2 3 [4] 5 6 .. 12');
        expectRanges(5, 12, 5, '1 2 3 4 [5] 6 7 .. 12');
        expectRanges(6, 12, 5, '1 .. 4 5 [6] 7 8 .. 12');
        expectRanges(7, 12, 5, '1 .. 5 6 [7] 8 9 .. 12');
        expectRanges(8, 12, 5, '1 .. 6 7 [8] 9 10 11 12');
        expectRanges(9, 12, 5, '1 .. 7 8 [9] 10 11 12');
        expectRanges(10, 12, 5, '1 .. 7 8 9 [10] 11 12');
        expectRanges(11, 12, 5, '1 .. 7 8 9 10 [11] 12');
        expectRanges(12, 12, 5, '1 .. 7 8 9 10 11 [12]');

    }
);

test(
    'Next/previous button behavior',
    function () {
        $pager = new ButtonPager(1, 3);

        ok(strpos($pager->render(), '<button disabled="disabled">&laquo;</button>') !== false,
            'previous-button disabled when on first page');

        ok(strpos($pager->render(), '<button name="page" value="2">&raquo;</button>') !== false,
            'next-button enabled when on first page');

        $pager = new ButtonPager(2, 3);

        ok(strpos($pager->render(), '<button name="page" value="1">&laquo;</button>') !== false,
            'previous-button enabled when not on first/last page');

        ok(strpos($pager->render(), '<button name="page" value="3">&raquo;</button>') !== false,
            'next-button enabled when not on first/last page');

        $pager = new ButtonPager(3, 3);

        ok(strpos($pager->render(), '<button name="page" value="2">&laquo;</button>') !== false,
            'previous-button enabled when on last page');

        ok(strpos($pager->render(), '<button disabled="disabled">&raquo;</button>') !== false,
            'next-button disabled when on last page');
    }
);

test(
    'ButtonPager works nicely',
    function () {
        $pager = new ButtonPager(1, 10);

        $expected = '<button disabled="disabled">&laquo;</button>&nbsp;'
            .'<button name="page" value="1" disabled="disabled" class="is-active">1</button>&nbsp;'
            .'<button name="page" value="2">2</button>&nbsp;'
            .'<button name="page" value="3">3</button>&nbsp;'
            .'<button name="page" value="4">4</button>&nbsp;'
            .'<button name="page" value="5">5</button>&nbsp;'
            .'&#x22EF;&nbsp;'
            .'<button name="page" value="10">10</button>&nbsp;'
            .'<button name="page" value="2">&raquo;</button>';

        eq($pager->render(), $expected, 'yay, buttons!');
    }
);

test(
    'LinkPager is snazzy',
    function () {
        $pager = new LinkPager(1, 3);

        $expected = '<a href="#">&laquo;</a>&nbsp;'
            .'<a href="#1" class="is-active">1</a>&nbsp;'
            .'<a href="#2">2</a>&nbsp;'
            .'<a href="#3">3</a>&nbsp;'
            .'<a href="#2">&raquo;</a>';

        eq($pager->render(), $expected, 'renders links with hash-tags');

        $pager->create_url = function($page) {
            return "result.php?page={$page}";
        };

        $expected = '<a href="#">&laquo;</a>&nbsp;'
            .'<a href="result.php?page=1" class="is-active">1</a>&nbsp;'
            .'<a href="result.php?page=2">2</a>&nbsp;'
            .'<a href="result.php?page=3">3</a>&nbsp;'
            .'<a href="result.php?page=2">&raquo;</a>';

        eq($pager->render(), $expected, 'renders links with URLs');
    }
);

exit(status());

/**
 * Test expectation of computed page-ranges
 *
 * @param int $active
 * @param int $total
 * @param int $range
 * @param string $expected
 *
 * @see Pager::getPageRanges()
 */
function expectRanges($active, $total, $range, $expected) {
    $ranges = Pager::getPageRanges($active, $total, $range);

    $result = implode(
        ' .. ',
        array_map(
            function ($range) use ($active) {
                $range = array_map(function ($page) use ($active) {
                    return $page == $active ? "[{$page}]" : $page;
                }, $range);
                return implode(' ', $range);
            },
            $ranges
        )
    );

    eq($result, $expected, sprintf('page %2d of %2d with a range of %2d', $active, $total, $range));
}

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
