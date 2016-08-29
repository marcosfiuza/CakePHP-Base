<?php
App::uses('FormHelper', 'View/Helper');

class BootstrapFormHelper extends FormHelper
{
    protected $_customInputDefaults = array(
        'label' => false,
        'div' => 'form-group',
        'class' => 'form-control',
        'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-block'))
    );
    
    public function inputDefaults($defaults = null, $merge = false)
    {
        $defaults = $this->_inputDefaults = array_merge($this->_customInputDefaults, (array)$defaults);
        
        return parent::inputDefaults($defaults, $merge);
	}
    
    public function addClass($options = array(), $class = NULL, $key = 'class')
    {
        if (strcasecmp($key, 'class') === 0 && strcasecmp($class, 'error') === 0)
        {
            $class = 'has-error';
        }
        
        return parent::addClass($options, $class, $key);
    }
    
    public function label($fieldName = null, $text = null, $options = array())
    {
        $options = array_merge($options, array('class' => 'control-label'));
        
        return parent::label($fieldName, $text, $options);
    }
}