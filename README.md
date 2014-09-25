mindplay/pager
==============

[![Build Status](https://travis-ci.org/mindplay-dk/pager.svg?branch=master)](https://travis-ci.org/mindplay-dk/pager)

How many times have I written pagers? Too many.

So here's a distilled abstract base-class for pagers, along with the two kinds of
pagers I have used most frequently: one for `<button>` tags, and one for `<a>` tags.

Personally I like `<button>` pagers, which typically require no JavaScript - it can
produce markup along the lines of this:

    <button disabled="disabled">&laquo;</button>
    <button name="page" value="1" disabled="disabled" class="is-active">1</button>&nbsp;
    <button name="page" value="2">2</button>&nbsp;
    <button name="page" value="3">3</button>&nbsp;
    <button name="page" value="4">4</button>&nbsp;
    <button name="page" value="5">5</button>&nbsp;&#x22EF;&nbsp;
    <button name="page" value="10">10</button>
    <button name="page" value="2">&raquo;</button>

Your average everyday link pager produces markup like this:

    <a href="#">&laquo;</a>
    <a href="result.php?page=1" class="is-active">1</a>&nbsp;
    <a href="result.php?page=2">2</a>&nbsp;
    <a href="result.php?page=3">3</a>
    <a href="result.php?page=2">&raquo;</a>

The markup in both cases is pretty minimal - I didn't want a bunch of configurable
CSS class-names, a container div, or boatloads of other options, but deriving your
own custom pager-classes should be pretty painless. (No guarantees, I said it *should* be.)
