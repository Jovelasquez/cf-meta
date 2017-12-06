<?php
namespace CustomFields\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Utility\Inflector;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

/**
 * ProcessForm component
 */
class ProcessFormComponent extends Component
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'configField' => [],
        'valuesField' => [],
    ];

    /**
     * Initialize properties.
     *
     * @param array $config The config data.
     * @return void
     */
    public function initialize(array $config)
    {

    }

    /**
     * Get Setting
     * 
     * Retorna la configuracion de los campos que extienden el modelo
     * 
     * @return object setting
     */
    public function getSetting()
    {
        $config = (object) $this->getConfig('configField');

        if(!$config){
            return false;
        }

        $setting = $this->_getSettingModel($config->custom_model, $config->custom_foreign_key);
        $setting = $this->_getAttributes($setting);
        //$setting = $this->_generateStructure($setting);
        return $setting;
    }
    
    /**
     * Recibe un JSON y retorna un Array
     * 
     * @return array
     */
    public function jsonToArray($string)
    {
        if(is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE)){
            return json_decode($string, true);
        }else{
            return [];
        }
    }

    /**
     * Retorna la configuracion de los campos para un modelo
     * 
     * @var $model Nombre del Modelo
     * @var $foreign_key Id del Registro
     * @return array
     */
    protected function _getSettingModel($model = null, $foreign_key = null)
    {
        $modelTable = TableRegistry::get('CustomFields.CustomFields');

        $results = $modelTable->find('configField', [
            'customModel' => $model,
            'customModelKey' => $foreign_key
        ])->order('position');

        return $results->toArray();
    }

    /**
     * get attributes input
     * 
     * 
     * @return array
     */
    protected function _getAttributes($fields = [])
    {
        if(is_array($fields)){
            foreach($fields as $key => $field){                
                $attrs = $this->jsonToArray($field['options']);
                if($attrs){
                    //Name field - multiple
                    $fields[$key]['field_name'] = __('custom_field_values.0.{0}', $field['id']);
                    
                    //Attribute
                    $fields[$key]['attributes'] = Hash::merge([
                        'label' => isset($attrs['properties']['label']) ? $attrs['properties']['label'] : $field['name'], 
                        'type' => $field['field_format'],
                        'required' => $field['is_required'],
                        
                    ], Hash::extract($attrs, 'properties')); 

                    if($fields[$key]['attributes']['type'] === 'select'){
                        if(isset($field['possible_values']) && !empty($field['possible_values'])){
                            $fields[$key]['attributes']['empty'] = $field['name'];
                            $fields[$key]['attributes']['options'] = self::jsonToArray($field['possible_values']);
                        }
                    }
                    
                    //Functions
                    if(Hash::check($attrs, 'function')){
                        $fields[$key]['function'] = Hash::merge([

                        ], Hash::extract($attrs, 'function')); 
                    }

                    //Column width
                    if(isset($attrs['columns']) && is_string($attrs['columns'])){
                        $fields[$key]['columns'] = $attrs['columns'];
                    }else{
                        $fields[$key]['columns'] = "col-md-12";
                    }
                    
                    //Parent for tree orden
                    $fields[$key]['parent'] = ($attrs['parent_id'] === null) ? 0 : $attrs['parent_id'];
                    unset($fields[$key]['options']);               
                }
            }
        }
//debug($fields);
        return $fields;
    }

    /**
     * 
     * 
     * @return array $structure
     */
    protected function _generateStructure($elements, $parent = null){
        $structure = [];

        foreach($elements as $element){
            if ($element['parent'] == $parent) {
                $children = $this->_generateStructure($elements, $element['id']);
                if($children){
                    $element['children'] = $children;
                }
                
                $structure[] = $element;
            }
        }

        return $structure;
    }
}
