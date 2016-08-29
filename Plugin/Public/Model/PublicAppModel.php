<?php
App::uses('String', 'Utility');
App::uses('AppModel', 'Model');

class PublicAppModel extends AppModel
{
    public $validationDomain = 'public';
    public $modelAttribute = '';
    public $slugPattern = '';
    public $slugGenerate = false;
    
    public function beforeSave($options = array())
    {
        $this->data[$this->alias]['updated_at'] = gmdate('Y-m-d H:i:s');
        
        if (empty($this->id) && empty($this->data[$this->alias]['id']))
        {
            $this->data[$this->alias]['hash'] = String::uuid();
            $this->data[$this->alias]['created_at'] = $this->data[$this->alias]['updated_at'];
            
            if (!empty($this->modelAttribute))
            {
                $this->data[$this->alias]['model'] = $this->modelAttribute;
            }
        }
        
        return parent::beforeSave($options);
    }
    
    public function afterSave($created, $options = array())
    {
        $this->slugGenerate = $this->slugGenerate || $created;
        
        if (!empty($this->slugPattern) && $this->slugGenerate && empty($this->data[$this->alias]['slug']))
        {
            $counter = 0;
            $record = $this->findById($this->id);
            
            do
            {
                $slug = String::insert($counter == 0 ? ':slug' : ':slug-:counter', array('slug' => Inflector::slug(strtolower(String::insert($this->slugPattern, $record[$this->alias])), '-'), 'counter' => $counter));
                
                $findConditions = array(
                    $this->alias . '.id <>' => $record[$this->alias]['id'],
                    $this->alias . '.slug' => $slug                    
                );
                
                if ($this->hasField('status'))
                {
                    $findConditions['NOT'] = array(
                        $this->alias . '.status' => array('deleted')
                    );
                }
                
                $recordCount = $this->find('count',
                    array(
                        'conditions' => $findConditions
                    )
                );
                
                $counter++;
            }
            while ($recordCount > 0);
            
            $this->saveField('slug', $slug, array('callbacks' => false));
        }
        
        parent::afterSave($created, $options);
    }
    
    public function delete($id = null, $cascade = true)
    {
        if (!$this->hasField('status'))
        {
            return parent::delete($id, $cascade);
        }
        
        if (is_numeric($id))
        {
            $this->id = $id;
        }
        
        if (!$this->exists())
        {
            return false;
        }
        
        $this->saveField('status', 'deleted', array('callbacks' => false));
        $this->id = false;
        
        return true;
    }
    
    public function fieldContentUnique($check)
    {
        $fieldName = key($check);
        
        $queryConditions = array(
            $this->alias . sprintf('.%s LIKE', $fieldName) => $check[$fieldName],
            'NOT' => array(
                $this->alias . '.status' => array('deleted')
            )
        );
        
        if (!empty($this->data[$this->alias]['id']))
        {
            $queryConditions[$this->alias . '.id <>'] = $this->data[$this->alias]['id'];
        }
        else if (!empty($this->id))
        {
            $queryConditions[$this->alias . '.id <>'] = $this->id;
        }
        
        $user = $this->find('count',
            array(
                'conditions' => $queryConditions
            )
        );
        
        return $user == 0;
    }
    
    public function generateRandomString($length = 10, $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        $charactersLength = strlen($characters);
        $randomString = '';
        
        for ($i = 0; $i < $length; $i++)
        {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        
        return $randomString;
    }
}
