<?php

namespace mindplay\pager;

/**
 * Abstract base class for classes that generate HTML pagers.
 */
abstract class Pager
{
    /** @var int active page number */
    public $active = 1;

    /** @var int total number of pages */
    public $total = 0;

    /** @var number of links per range */
    public $range = 5;

    /** @var string HTML space between pager links */
    public $space = '&nbsp;';

    /** @var string HTML ellipsis between ranges */
    public $ellipsis = '&#x22EF;';

    /** @var string HTML previous (left) page link label */
    public $prev = '&laquo;';

    /** @var string HTML next (right) page link label */
    public $next = '&raquo;';

    /**
     * @param string $name   variable name
     * @param int    $active active page number
     * @param int    $total  total number of pages
     */
    public function __construct($active = 1, $total = 0)
    {
        $this->active = $active;
        $this->total = $total;
    }

    /**
     * @ignore
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * @return string
     */
    public function render()
    {
        if ($this->active == 0 || $this->total == 0 || $this->total == 1) {
            return ''; // no pager necessary
        }

        // previous page link:

        $html = ($this->active == 1)
            ? $this->renderDisabled($this->prev)
            : $this->renderLink($this->active - 1, $this->prev, false);

        $html .= $this->space;

        // numbered page links:

        $html_ranges = array();

        $ranges = static::getPageRanges($this->active, $this->total, $this->range);

        foreach ($ranges as $range) {
            $html_links = array();

            foreach ($range as $page) {
                $html_links[] = $this->renderLink($page, $page, $page == $this->active);
            }

            $html_ranges[] = implode($this->space, $html_links);

            unset($html_links);
        }

        $html .= implode($this->space . $this->ellipsis . $this->space, $html_ranges);

        unset($html_ranges);

        // next page link:

        $html .= $this->space;

        $html .= ($this->active < $this->total)
            ? $this->renderLink($this->active + 1, $this->next, false)
            : $this->renderDisabled($this->next);

        return $html;
    }

    /**
     * Compute page ranges for the {@see pager()} links.
     *
     * @param int $active the active page
     * @param int $total  the total number of pages
     * @param int $range  maximum number of pages in a range
     *
     * @return array int[][] ranges of page numbers
     */
    static function getPageRanges($active, $total, $range)
    {
        if ($active === 0 || $total === 0) {
            return array(); // no ranges
        }

        $range = max(3, $range % 2 ? $range : $range + 1); // nearest odd number

        $spread = (int) (($range - 1) * 0.5); // range in either direction

        if ($total <= $range + 4) {
            // full range:
            return array(
                range(1, $total)
            );
        }

        $left = $active - $spread;
        $right = $active + $spread;

        if ($left <= $spread + 1) {
            // left range:
            return array(
                range(1, max($range, $right)),
                array($total)
            );
        }

        if ($right >= $total - $spread) {
            // right range:
            return array(
                array(1),
                range(min(max($left, 1), $total - $range), $total)
            );
        }

        return array(
            // middle range:
            array(1),
            range($left, $right),
            array($total)
        );
    }

    /**
     * Render an individual pager link.
     *
     * @param int    $page    page number
     * @param string $label   HTML label
     * @param bool   $current true, if this is the currently active page number link
     *
     * @return string HTML
     */
    abstract protected function renderLink($page, $label, $current);

    /**
     * Render a disabled link (e.g. "next" or "previous" links, when those are disabled)
     *
     * @param string $label HTML label
     *
     * @return string HTML
     */
    abstract protected function renderDisabled($label);
}
