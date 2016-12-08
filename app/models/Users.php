<?php

use Phalcon\Mvc\Model\Validator\Email as Email;

class Users extends PrfModel
{

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation()
    {
        $this->validate(
            new Email(
                array(
                    'field'    => 'email',
                    'required' => true,
                )
            )
        );

        if ($this->validationHasFailed() == true) {
            return false;
        }

        return true;
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users[]
     */
  static function initStatic($oModel=null) {
    parent::initStatic($oModel);
    $curGroup = Hl::user('group_id');
    static::$fields['group_id']['foreign_filter'] = 'id>'.$curGroup;
    if ($curGroup > 1)
      static::$fields['parent_id']['noEdit'] = 'noEdit';
  }

}
