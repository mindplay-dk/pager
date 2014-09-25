<?php

namespace mindplay\pager;

use Closure;

class LinkPager extends Pager
{
    /** @var Closure|null function($page):string */
    public $create_url;

    /** @var string class-name applied when link is active */
    public $active_class = 'is-active';

    /**
     * @inheritdoc
     */
    protected function renderLink($page, $label, $current)
    {
        $href = $this->create_url
            ? call_user_func($this->create_url, $page)
            : '#' . $page;

        return $current
            ? '<a href="' . $href . '" class="' . $this->active_class . '">' . $label . '</a>'
            : '<a href="' . $href . '">' . $label . '</a>';
    }

    /**
     * @inheritdoc
     */
    protected function renderDisabled($label)
    {
        return '<a href="#">' . $label . '</a>';
    }
}
