<?php

namespace mindplay\pager;

class ButtonPager extends Pager
{
    /** @var string name-attribute for HTML button-tags */
    public $name = 'page';

    /** @var string class-name applied when button is active */
    public $active_class = 'is-active';

    /**
     * @inheritdoc
     */
    protected function renderLink($page, $label, $current)
    {
        return $current
            ? '<button name="' . $this->name . '" value="' . $page . '" disabled="disabled" class="' . $this->active_class . '">' . $label . '</button>'
            : '<button name="' . $this->name . '" value="' . $page . '">' . $label . '</button>';
    }

    /**
     * @inheritdoc
     */
    protected function renderDisabled($label)
    {
        return '<button disabled="disabled">' . $label . '</button>';
    }
}
