<?php

namespace CustomFields\View;

/**
 * Adds string template functionality to any class by providing methods to
 * load and parse string templates.
 *
 * This trait requires the implementing class to provide a `config()`
 * method for reading/updating templates. An implementation of this method
 * is provided by `Cake\Core\InstanceConfigTrait`
 */
trait FlexibleStringTemplateTrait {

    /**
     * Returns the templater instance.
     *
     * @return \Cake\View\StringTemplate
     */
    public function templater() {
        if ($this->_templater === null) {
            $class = $this->getConfig('templateClass') ?: 'CustomFields\View\FlexibleStringTemplate';
            $callback = $this->getConfig('templateCallback') ?: null;
            $callbacks = $this->getConfig('templateCallbacks') ?: [];
            $this->_templater = new $class([], $callback, $callbacks);
            $templates = $this->getConfig('templates');
            if ($templates) {
                if (is_string($templates)) {
                    $this->_templater->add($this->_defaultConfig['templates']);
                    $this->_templater->load($templates);
                }
                else {
                    $this->_templater->add($templates);
                }
            }
        }
        return $this->_templater;
    }
};
