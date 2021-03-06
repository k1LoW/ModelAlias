<?php
trait ModelAliasTrait
{
    public $usesAlias = [];

    /**
     * initUsesAlias
     *
     */
    public function initUsesAlias()
    {
        if (empty($this->uses)) {
            return;
        }
        $renamed = [];
        foreach ($this->uses as $key => $value) {
            if (!is_array($value)) {
                $renamed[] = $value;
                continue;
            }
            $this->usesAlias[$key] = $value['className'];
            $renamed[] = 'ModelAlias.'.$key;
        }
        $this->uses = $renamed;
    }

    /**
     * loadAliasModel
     *
     */
    public function loadAliasModel($modelClass, $id)
    {
        if (preg_match('/^ModelAlias\.(.+)/', $modelClass, $matches)) {
            $alias = $matches[1];

            $this->uses = ($this->uses) ? (array) $this->uses : array();
            if (in_array($modelClass, $this->uses, true)) {
                foreach ($this->uses as $key => $value) {
                    if ($modelClass === $value) {
                        $this->uses[$key] = $alias;
                    }
                }
            }
            if (!in_array($alias, $this->uses, true)) {
                $this->uses[] = $alias;
            }

            // Load alias model
            $modelClass = $this->usesAlias[$alias];
            ClassRegistry::removeObject($alias);
            $this->{$alias} = ClassRegistry::init(array(
                'class' => $modelClass, 'name' => $modelClass, 'alias' => $alias, 'id' => $id
            ));

            $this->{$alias}->alias = $alias;

            if (!$this->{$alias}) {
                throw new MissingModelException($modelClass);
            }

            return true;
        } else {
            return parent::loadModel($modelClass, $id);
        }
    }
}
