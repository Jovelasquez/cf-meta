<?php
/**
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE file
 * Redistributions of files must retain the above copyright notice.
 * You may obtain a copy of the License at
 *
 *     https://opensource.org/licenses/mit-license.php
 *
 *
 * @copyright Copyright (c) Mikaël Capelle (https://typename.fr)
 * @license https://opensource.org/licenses/mit-license.php MIT License
 */
namespace CustomFields\View\Widget;

use Cake\View\Widget\WidgetInterface;
use Cake\View\Form\ContextInterface;

class SearchWidget implements WidgetInterface {
    
    protected $_templates;
    
    /**
     * 
     * 
     */
    public function __construct($templates)
    {
        $this->_templates = $templates;
    }

    /**
     * 
     * 
     * 
     */
    public function render(array $data, ContextInterface $context)
    {
        $data += [
            'name' => 'asdasd',
        ];

        $field = $this->_templates->format('search', [
            'name' => $data['name'],
            'attrs' => $this->_templates->formatAttributes($data, ['name'])
        ]);

        return $field;
    }

    /**
     * 
     * 
     * 
     */
    public function secureFields(array $data)
    {
        return [$data['name']];
    }

}
