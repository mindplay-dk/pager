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

        $html .= ($this->active == 1)
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

        if ($total <= $range + 2) {
            // all numbers fit in a single range:
            return array(range(1, $total));
        }

        if ($active <= $range) {
            // numbers partition on the right:
            return array(
                range(1, $range), // first pages
                array($total) // last page
            );
        }

        if ($active >= $total - $range + 1) {
            // numbers partition on the left:
            return array(
                array(1), // first page
                range($total - $range + 1, $total) // last pages
            );
        }

        // numbers partition both on the left and on the right:
        $half = (int) floor($range * 0.5);
        return array(
            array(1), // first page
            range($active - $half, $active + $half), // middle pages
            array($total) // last page
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
