<?php
namespace Phalcon\UserPlugin\Forms\User;

use Phalcon\Forms\Form,
Phalcon\Forms\Element\Text,
Phalcon\Forms\Element\Password,
Phalcon\Forms\Element\Submit,
Phalcon\Forms\Element\Check,
Phalcon\Forms\Element\Hidden,
Phalcon\Validation\Validator\PresenceOf,
Phalcon\Validation\Validator\Email,
Phalcon\Validation\Validator\Identical;

/**
 * Phalcon\UserPlugin\Forms\User\LoginForm
 */
class LoginForm extends Form
{
    public function initialize()
    {
        //Email
        $email = new Text('email', [
            'required' => 'required',
            'placeholder' => 'Email',
            'class' => 'form-control',
        ]);

        $email->addValidators(array(
            new PresenceOf(array(
                'message' => 'The e-mail is required'
            )),
            new Email(array(
                'message' => 'The e-mail is not valid'
            ))
        ));

        $this->add($email);

        //Password
        $password = new Password('password', array(
          'required' => 'required',
          'placeholder' => 'Password',
          'class' => 'form-control',
        ));

        $password->addValidator(
            new PresenceOf(array(
                'message' => 'The password is required'
            ))
        );

        $this->add($password);

        //Remember
        $remember = new Check('remember', array(
            'value' => 'yes'
        ));

        $remember->setLabel('Remember me');

        $this->add($remember);

        //CSRF
        $csrf = new Hidden('csrf',array(
          'value' => $this->security->getSessionToken(),
        ));

        $csrf->addValidator(
            new Identical(array(
                'value' => $this->security->getSessionToken(),
                'message' => 'CSRF validation failed'
            ))
        );

        $this->add($csrf);

        $this->add(new Submit('go', [
            'class' => 'btn btn-default submit',
        ]
        ));
    }
}
