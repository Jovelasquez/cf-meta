<?php

namespace CustomFields\View;

use Cake\View\StringTemplate;
use RuntimeException;

class EnhancedStringTemplate extends StringTemplate {

    /**
     * General callback function.
     *
     * @var callable
     */
    protected $_callback = null;

    /**
     * Array of callback function for specific templates.
     *
     * @var array
     */
    protected $_callbacks = null;

    /**
     * Compile templates into a more efficient printf() compatible format.
     *
     * @param array $templates The template names to compile. If empty all templates will
     * be compiled.
     *
     * @return void
     */
    protected function _compileTemplates(array $templates = []) {
        if (empty($templates)) {
            $templates = array_keys($this->_config);
        }
        foreach ($templates as $name) {
            $template = $this->get($name);
            if ($template === null) {
                $this->_compiled[$name] = [null, null];
            }
            $template = str_replace('%', '%%', $template);
            preg_match_all('#\{\{([\w.]+)\}\}#', $template, $matches);
            $this->_compiled[$name] = [
                str_replace($matches[0], '%s', $template),
                $matches[1]
            ];
        }
    }

    /**
     * Format a template string with $data
     *
     * @param string $name The template name.
     * @param array  $data The data to insert.
     *
     * @return string
    */
    public function format($name, array $data) {
        if (!isset($this->_compiled[$name])) {
            throw new RuntimeException("Cannot find template named '$name'.");
        }
        list($template, $placeholders) = $this->_compiled[$name];
        // If there is a {{attrs.xxxx}} block in $template, remove the xxxx attribute
        // from $data['attrs'] and add its content to $data['attrs.class'].
        if (isset($data['attrs'])) {
            foreach ($placeholders as $placeholder) {
                if (substr($placeholder, 0, 6) == 'attrs.'
                    && preg_match('#'.substr($placeholder, 6).'="([^"]*)"#',
                                  $data['attrs'], $matches) > 0) {
                    $data['attrs'] = preg_replace('#'.substr($placeholder, 6).'="[^"]*"#',
                                                  '', $data['attrs']);
                    $data[$placeholder] = trim($matches[1]);
                    if ($data[$placeholder]) {
                        $data[$placeholder] = ' '.$data[$placeholder];
                    }
                }
            }
            $data['attrs'] = trim($data['attrs']);
            if ($data['attrs']) {
                $data['attrs'] = ' '.$data['attrs'];
            }
        }
        return parent::format($name, $data);
    }

};
