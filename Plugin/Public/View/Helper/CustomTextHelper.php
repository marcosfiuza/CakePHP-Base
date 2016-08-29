<?php
App::uses('TextHelper', 'View/Helper');

class CustomTextHelper extends TextHelper
{
    public function emptyMessage($text, $message = '')
    {
        if (empty($text))
        {
            return $message;
        }
        
        return $text;
    }
}