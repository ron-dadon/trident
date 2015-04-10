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
 * Class Html_Library
 *
 * Wrapper for all common html5 tags
 */
class Html_Library extends Trident_Abstract_Library
{
    /**
     * Public base path
     *
     * @var string
     */
    private $_base;

    /**
     * JavaScript folder path
     *
     * @var string
     */
    private $_js;

    /**
     * CSS folder path
     *
     * @var string
     */
    private $_css;

    function __construct($_configuration = null, $database = null, $io = null, $log = null, $_request = null, $_session = null)
    {
        parent::__construct($_configuration, $database, $io, $log, $_request, $_session);
        if ($this->configuration !== null)
        {
            $this->_base = $this->configuration->get('paths', 'public');
            $this->set_paths();
        }
    }

    /**
     * Set paths for css and javascript folders
     *
     * @param string $css css folder name
     * @param string $js  javascript folder name
     */
    public function set_paths($css = '/css', $js = '/js')
    {
        $this->_css = '/' . trim($css, '/') . '/';
        $this->_js = '/' . trim($js, '/') . '/';
    }

    /**
     * Create a html5 open tag
     *
     * @param string $lang The html page primary language
     *
     * @return string           String representing the opening html tag
     */
    public function html_open($lang = 'en')
    {
        return '<!DOCTYPE html>' . PHP_EOL . '<html lang="' . $lang . '">' . PHP_EOL;
    }

    /**
     * Create a html close tag
     *
     * @return string   String representing the closing html tag
     */
    public function html_close()
    {
        return '</html>';
    }

    /**
     * Create a body open tag
     *
     * @return string   String representing the opening body tag
     */
    public function body_open()
    {
        return '<body>' . PHP_EOL;
    }

    /**
     * Create a body close tag
     *
     * @return string   String representing the closing body tag
     */
    public function body_close()
    {
        return '</body>' . PHP_EOL;
    }

    /**
     * Create a html header element
     *
     * @param string $content Content of the head (stylesheets, javascript files, metadata etc.)
     *
     * @return string           String representing the html head element
     */
    public function html_header($content)
    {
        return '<head>' . PHP_EOL . $content . '</head>' . PHP_EOL;
    }

    /**
     * Create a link element for linking css files
     *
     * @param string $file The css file name
     *
     * @return string       String representing the link element
     */
    public function css_link($file)
    {
        return '<link rel="stylesheet" type="text/css" href="' . $this->_base . $this->_css . $file . '">' . PHP_EOL;
    }

    /**
     * Create a script element with source javascript file
     *
     * @param string $file The js file name
     *
     * @return string       String representing the script element
     */
    public function javascript_link($file)
    {
        return '<script src="' . $this->_base . $this->_js . $file . '"></script>' . PHP_EOL;
    }

    /**
     * Create a script element
     *
     * @param string $code The code within the element
     *
     * @return string       String representing the script element
     */
    public function script($code)
    {
        return $this->element('script', $code);
    }

    /**
     * Create a script element
     *
     * @param string $code The code within the element
     *
     * @return string       String representing the script element
     */
    public function style($code)
    {
        return $this->element('style', $code);
    }

    /**
     * Create a HTML element string
     *
     * @param string $type       Element type
     * @param string $content    Content of the element
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the element
     */
    public function element($type, $content, $attributes = [])
    {
        $attributesArray = [];
        foreach ($attributes as $attr => $value)
        {
            $attributesArray[] = $attr . '="' . $value . '"';
        }
        $attributesArray = implode(' ', $attributesArray);
        if (strlen($attributesArray))
        {
            $element = "<$type $attributesArray>$content</$type>" . PHP_EOL;
        }
        else
        {
            $element = "<$type>$content</$type>" . PHP_EOL;
        }
        return $element;
    }

    /**
     * Create a address element
     *
     * @param string $content Content of the address
     *
     * @return string               String representing the address element
     */
    public function address($content)
    {
        return $this->element('address', $content);
    }

    /**
     * Create an anchor element
     *
     * @param string $link       URL for the anchor link
     * @param string $content    Content of the anchor
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the anchor
     */
    public function anchor($link, $content, $attributes = [])
    {
        $attributes['href'] = $link;
        return $this->element('a', $content, $attributes);
    }

    /**
     * Create a article element
     *
     * @param string $content    Content of the article
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the article
     */
    public function article($content, $attributes = [])
    {
        return $this->element('article', $content, $attributes);
    }

    /**
     * Create a aside element
     *
     * @param string $content    Content of the aside
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the aside
     */
    public function aside($content, $attributes = [])
    {
        return $this->element('aside', $content, $attributes);
    }

    /**
     * Create a audio element
     *
     * @param string $srcMp3     MP3 file source path (full path)
     * @param string $srcOgg     OGG file source path (full path)
     * @param string $noSupport  Message to display if browser doesn't support audio element
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the audio
     */
    public function audio($srcMp3, $srcOgg = '', $noSupport = 'No support', $attributes = [])
    {
        $source = '<source src="' . $srcMp3 . '" type="audio/mpeg">';
        if ($srcOgg !== '')
        {
            $source .= PHP_EOL . '<source src="' . $srcOgg . '" type="audio/ogg">';
        }
        $source .= PHP_EOL . $noSupport;
        return $this->element('audio', $source, $attributes);
    }

    /**
     * Create a BDO (Bi-Directional Override) element
     *
     * @param string $content    Content of the BDO
     * @param string $direction  Direction of the BDO (ltr|rtl)
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the BDO, or empty string if direction is invalid
     */
    public function bdo($content, $direction = 'ltr', $attributes = [])
    {
        if (strtolower($direction) === 'ltr' || strtolower($direction) === 'rtl')
        {
            $attributes['dir'] = $direction;
            return $this->element('bdo', $content, $attributes);
        }
        return '';
    }

    /**
     * Create an block quote element
     *
     * @param string $content    Content of the anchor
     * @param string $link       URL for the block quote source link
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the block quote  if link if valid or empty, empty string
     *                              otherwise
     */
    public function block_quote($content, $link, $attributes = [])
    {
        if ($link == '' || filter_var($link, FILTER_VALIDATE_URL))
        {
            $attributes['cite'] = $link;
            return $this->element('blockquote', $content, $attributes);
        }
        return '';
    }

    /**
     * Create a line break element
     *
     * @return string String representing line break
     */
    public function break_line()
    {
        return '<br>';
    }

    /**
     * Create a button element
     *
     * @param string $content    Content of the button
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the button
     */
    public function button($content, $attributes = [])
    {
        return $this->element('button', $content, $attributes);
    }

    /**
     * Create a canvas element
     *
     * @param array $attributes Associative array of attributes and values
     *
     * @return string               String representing the canvas
     */
    public function canvas($attributes = [])
    {
        return $this->element('canvas', '', $attributes);
    }

    /**
     * Create a cite element
     *
     * @param string $content    Content of the cite
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the cite
     */
    public function cite($content, $attributes = [])
    {
        return $this->element('cite', $content, $attributes);
    }

    /**
     * Create a description list item element
     *
     * @param string $term        Content of the cite
     * @param string $description Content of the cite
     *
     * @return string               String representing the description list item
     */
    public function description_list_item($term, $description)
    {
        return $this->element('dt', $term) . $this->element('dd', $description);
    }

    /**
     * Create a description list element
     *
     * @param array $items      Items of the list
     * @param array $attributes Associative array of attributes and values
     *
     * @return string               String representing the description list item
     */
    public function description_list($items, $attributes = [])
    {
        return $this->element('cite', implode(PHP_EOL, $items), $attributes);
    }

    /**
     * Create a div element
     *
     * @param string $content    Content of the div
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the div
     */
    public function div($content, $attributes = [])
    {
        return $this->element('div', $content, $attributes);
    }

    /**
     * Create a emphasized element
     *
     * @param string $content    Content of the emphasized
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the emphasized
     */
    public function emphasized($content, $attributes = [])
    {
        return $this->element('em', $content, $attributes);
    }

    /**
     * Create a embed element
     *
     * @param string $file       The embedded file name
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the embed element
     */
    public function embed($file, $attributes = [])
    {
        $attributes['src'] = $file;
        $result = $this->element('embed', '', $attributes);
        return str_replace('</embed>', '', $result);
    }

    /**
     * Create a field set element
     *
     * @param string $content    Content of the field set
     * @param string $legend     Legend (title) of the field set
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the field set
     */
    public function field_set($content, $legend = '', $attributes = [])
    {
        if ($legend !== '')
        {
            $content = $this->element('legend', $legend) . $content;
        }
        return $this->element('fieldset', $content, $attributes);
    }

    /**
     * Create a figure element
     *
     * @param string $caption    Caption of the figure
     * @param string $file       Source file for figure image (full path)
     * @param array  $attributes Associative array of attributes and values for image and figure tags
     *
     * @return string               String representing the figure
     */
    public function figure($caption, $file, $attributes = [])
    {
        $content = $this->image($file, $attributes['image']);
        $content .= '<figcaption>' . $caption . '</figcaption>' . PHP_EOL;
        return $this->element('figure', $content, $attributes['figure']);
    }

    /**
     * Create a image element
     *
     * @param string $file       The image file name (full path)
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the image
     */
    public function image($file, $attributes = [])
    {
        $attributes['src'] = $file;
        $result = $this->element('img', '', $attributes);
        return str_replace('</img>', '', $result);
    }

    /**
     * Create a footer element
     *
     * @param string $content    Content of the footer
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the footer
     */
    public function footer($content, $attributes = [])
    {
        return $this->element('footer', $content, $attributes);
    }

    /**
     * Create a form element
     *
     * @param string $content    Content of the form
     * @param string $method     Form's method
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the form
     */
    public function form($content, $method = 'post', $attributes = [])
    {
        $attributes['method'] = $method;
        return $this->element('form', $content, $attributes);
    }

    /**
     * Create a header element
     *
     * @param int    $size       Size of the header
     * @param string $content    Content of the header
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the header  if size if valid, empty string otherwise
     */
    public function h($size, $content, $attributes = [])
    {
        if ($size >= 1 && $size <= 6)
        {
            return $this->element('h' . $size, $content, $attributes);
        }
        return '';
    }

    /**
     * Create a header element
     *
     * @param string $content    Content of the header
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the header
     */
    public function header($content, $attributes = [])
    {
        return $this->element('header', $content, $attributes);
    }

    public function horizontalLine()
    {
    }

    /**
     * Create a i element
     *
     * @param string $content    Content of the i
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the i
     */
    public function i($content, $attributes = [])
    {
        return $this->element('i', $content, $attributes);
    }

    /**
     * Create a iframe element
     *
     * @param string $url        The iframe source url (full path)
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the iframe if the url is valid, empty string otherwise
     */
    public function iframe($url, $attributes = [])
    {
        if (filter_var($url, FILTER_VALIDATE_URL))
        {
            $attributes['src'] = $url;
            return $this->element('iframe', '', $attributes);
        }
        return '';
    }

    /**
     * Create an input element
     *
     * @param string $name       Name of the input
     * @param string $type       Type of the input (text, email, date, number etc.)
     * @param string $value      Value of the input
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the input
     */
    public function input($name, $type = 'text', $value = '', $attributes = [])
    {
        $attributes['type'] = $type;
        $attributes['name'] = $name;
        $attributes['value'] = $value;
        $result = $this->element('input', '', $attributes);
        return str_replace('</input>', '', $result);
    }

    /**
     * Create a ins element
     *
     * @param string $content    Content of the ins
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the ins
     */
    public function ins($content, $attributes = [])
    {
        return $this->element('ins', $content, $attributes);
    }

    /**
     * Create a kbd element
     *
     * @param string $content    Content of the kbd
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the kbd
     */
    public function kbd($content, $attributes = [])
    {
        return $this->element('kbd', $content, $attributes);
    }

    /**
     * Create a label element
     *
     * @param string $content    Content of the label
     * @param string $for        ID of the element that the label refers to
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the label
     */
    public function label($content, $for = '', $attributes = [])
    {
        $attributes['for'] = $for;
        return $this->element('label', $content, $attributes);
    }

    /**
     * Create a list item element
     *
     * @param string $content    Content of the list item
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the list item
     */
    public function list_item($content, $attributes = [])
    {
        return $this->element('li', $content, $attributes);
    }

    /**
     * Create a main element
     *
     * @param string $content    Content of the main
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the main
     */
    public function main($content, $attributes = [])
    {
        return $this->element('main', $content, $attributes);
    }

    /**
     * Create a mark element
     *
     * @param string $content    Content of the mark
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the mark
     */
    public function mark($content, $attributes = [])
    {
        return $this->element('mark', $content, $attributes);
    }

    /**
     * Create a meta tag
     *
     * @param array $attributes Associative array of attributes and values
     *
     * @return string               String representing the span
     */
    public function meta($attributes = [])
    {
        $result = $this->element('meta', '', $attributes);
        return str_replace('</meta>', '', $result);
    }

    /**
     * Create a nav element
     *
     * @param string $content    Content of the nav
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the nav
     */
    public function nav($content, $attributes = [])
    {
        return $this->element('nav', $content, $attributes);
    }

    /**
     * Create a no script element
     *
     * @param string $content    Content of the no script
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the no script
     */
    public function no_script($content, $attributes = [])
    {
        return $this->element('noscript', $content, $attributes);
    }

    /**
     * Create a object element
     *
     * @param string $url        The object source url (full path)
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the object if the url is valid, empty string otherwise
     */
    public function object($url, $attributes = [])
    {
        if (filter_var($url, FILTER_VALIDATE_URL))
        {
            $attributes['data'] = $url;
            return $this->element('object', '', $attributes);
        }
        return '';
    }

    /**
     * Create an ordered list element
     *
     * @param array $items      Items for the list
     * @param array $attributes Associative array of attributes and values
     *
     * @return string               String representing the ordered list
     */
    public function ordered_list($items = [], $attributes = [])
    {
        $content = implode(PHP_EOL, $items);
        return $this->element('ol', $content, $attributes);
    }

    /**
     * Create select element
     *
     * @param array  $items      List of options or option sets with options (value => label)
     * @param string $name       The name of the element
     * @param string $selected   The selected value
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the select
     */
    public function select($items, $name, $selected = '', $attributes = [])
    {
        $options = [];
        foreach ($items as $value => $label)
        {
            if (is_array($label))
            {
                $subOptions = [];
                foreach ($label as $subValue => $subLabel)
                {
                    $attr = ['value' => $subValue];
                    if ($subValue == $selected)
                    {
                        $attr['selected'] = 'selected';
                    }
                    $subOptions[] = $this->element('option', $label, $attr);
                }
                $options[] = $this->element('optgroup', implode('', $subOptions), ['label' => $value]);
            }
            else
            {
                $attr = ['value' => $value];
                if ($value == $selected)
                {
                    $attr['selected'] = 'selected';
                }
                $options[] = $this->element('option', $label, $attr);
            }
        }
        $attributes['name'] = $name;
        return $this->element('select', implode('', $options), $attributes);
    }

    /**
     * Create a paragraph element
     *
     * @param string $content    Content of the paragraph
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the paragraph
     */
    public function paragraph($content, $attributes = [])
    {
        return $this->element('p', $content, $attributes);
    }

    /**
     * Create a pre element
     *
     * @param string $content    Content of the pre
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the pre
     */
    public function pre($content, $attributes = [])
    {
        return $this->element('pre', $content, $attributes);
    }

    /**
     * Create a progress element
     *
     * @param string $value      Value of the progress
     * @param string $max        Maximum allowed value of the progress
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the progress
     */
    public function progress($value, $max = '100', $attributes = [])
    {
        $attributes['max'] = $max;
        $attributes['value'] = $value;
        return $this->element('progress', '', $attributes);
    }

    /**
     * Create a quote element
     *
     * @param string $content    Content of the quote
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the quote
     */
    public function quote($content, $attributes = [])
    {
        return $this->element('q', $content, $attributes);
    }

    /**
     * Create a section element
     *
     * @param string $content    Content of the section
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the section
     */
    public function section($content, $attributes = [])
    {
        return $this->element('section', $content, $attributes);
    }

    /**
     * Create a small element
     *
     * @param string $content    Content of the small
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the small
     */
    public function small($content, $attributes = [])
    {
        return $this->element('small', $content, $attributes);
    }

    /**
     * Create a span element
     *
     * @param string $content    Content of the span
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the span
     */
    public function span($content, $attributes = [])
    {
        return $this->element('span', $content, $attributes);
    }

    /**
     * Create a strong element
     *
     * @param string $content    Content of the strong
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the strong
     */
    public function strong($content, $attributes = [])
    {
        return $this->element('strong', $content, $attributes);
    }

    /**
     * Create a sub script element
     *
     * @param string $content    Content of the sub script
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the sub script
     */
    public function sub_script($content, $attributes = [])
    {
        return $this->element('sub', $content, $attributes);
    }

    /**
     * Create a super script element
     *
     * @param string $content    Content of the super script
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the super script
     */
    public function super_script($content, $attributes = [])
    {
        return $this->element('sup', $content, $attributes);
    }

    /**
     * Create a table element
     *
     * @param array   $cols       Table columns titles
     * @param array[] $cells      Table data (cells - 2D array)
     * @param array   $footer     Table footer cells
     * @param array   $attributes Associative array of attributes and values
     *
     * @return string               String representing the super table
     */
    public function table($cols, $cells, $footer, $attributes = [])
    {
        $columns = '<tr>' . PHP_EOL;
        foreach ($cols as $col)
        {
            $columns .= $this->element('th', $col);
        }
        $columns .= '</tr>' . PHP_EOL;
        $rows = '';
        foreach ($cells as $row)
        {
            $rows .= '<tr>' . PHP_EOL;
            foreach ($row as $cell)
            {
                $rows .= $this->element('td', $cell);
            }
            $rows .= '</tr>' . PHP_EOL;
        }
        $foot = '<tr>' . PHP_EOL;
        foreach ($footer as $col)
        {
            $foot .= $this->element('td', $col);
        }
        $foot .= '</tr>' . PHP_EOL;
        return $this->element('table',
            $this->element('thead', $columns) .
            $this->element('tbody', $rows) .
            $this->element('tfoot', $foot), $attributes);
    }

    /**
     * Create a text area element
     *
     * @param string $content    Content of the text area
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the text area
     */
    public function text_area($content, $attributes = [])
    {
        return $this->element('textarea', $content, $attributes);
    }

    /**
     * Create a time element
     *
     * @param string $content    Content of the time
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the time
     */
    public function time($content, $attributes = [])
    {
        return $this->element('time', $content, $attributes);
    }

    /**
     * Create a title element
     *
     * @param string $content    Content of the title
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the title
     */
    public function title($content, $attributes = [])
    {
        return $this->element('title', $content, $attributes);
    }

    /**
     * Create an underlined element
     *
     * @param string $content    Content of the underlined
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the underlined
     */
    public function underlined($content, $attributes = [])
    {
        return $this->element('u', $content, $attributes);
    }

    /**
     * Create an unordered list element
     *
     * @param array $items      Items for the list
     * @param array $attributes Associative array of attributes and values
     *
     * @return string               String representing the unordered list
     */
    public function unordered_list($items = [], $attributes = [])
    {
        $content = implode(PHP_EOL, $items);
        return $this->element('ul', $content, $attributes);
    }

    /**
     * Create a variable element
     *
     * @param string $content    Content of the variable
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the variable
     */
    public function variable($content, $attributes = [])
    {
        return $this->element('var', $content, $attributes);
    }

    /**
     * Create a video element
     *
     * @param string $srcMp4     MP4 file source path (full path)
     * @param string $srcOgg     OGG file source path (full path)
     * @param string $noSupport  Message to display if browser doesn't support video element
     * @param array  $attributes Associative array of attributes and values
     *
     * @return string               String representing the video
     */
    public function video($srcMp4, $srcOgg = '', $noSupport = 'No support', $attributes = [])
    {
        $source = '<source src="' . $srcMp4 . '" type="video/mp4">';
        if ($srcOgg !== '')
        {
            $source .= PHP_EOL . '<source src="' . $srcOgg . '" type="video/ogg">';
        }
        $source .= PHP_EOL . $noSupport;
        return $this->element('video', $source, $attributes);
    }

    /**
     * Escape html special characters
     *
     * @param string $var The value for escaping
     *
     * @return string           The escaped value
     */
    public function escape($var)
    {
        return htmlspecialchars($var, ENT_QUOTES | ENT_HTML5);
    }

    /**
     * Encode data to base64 string
     *
     * @param mixed $data
     *
     * @return string
     */
    public function base64Encode($data)
    {
        return base64_encode($data);
    }
}