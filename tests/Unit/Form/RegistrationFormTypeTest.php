<?php

namespace App\Tests\Unit\Form;

// tests/Form/Type/TestedTypeTest.php

use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;

class RegistrationFormTypeTest extends TypeTestCase
{
    protected function getExtensions(): array {
        $validator = Validation::createValidator();

        return [
            new ValidatorExtension($validator),
        ];
    }

    public function testBuildForm()
    {
        $formData = [
            'username' => 'test_user',
            'plainPassword' => 'password',
            'agreeTerms' => true
        ];

        $modal = new User();
        $form = $this->factory->create(RegistrationFormType::class,$modal);

        $user = new User();
        $user->setUsername('test_user');
        $user->setPassword('password');
        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        //$this->assertEquals($user, $modal);
        $this->assertEquals('test_user', $modal->getUsername());

        // Test the constraints on the username field
        $usernameField = $form->get('username');
        $this->assertTrue($usernameField->isRequired());
        $this->assertEquals('username', $usernameField->getConfig()->getOption('attr')['placeholder']);
        $this->assertEquals('bg-transparent block mt-10 mx-auto border-b-2 w-1/5 h-20 text-2xl outline-none', $usernameField->getConfig()->getOption('attr')['class']);

        // Test the constraints on the agreeTerms field
        $agreeTermsField = $form->get('agreeTerms');
        $this->assertTrue($agreeTermsField->isRequired());
        $this->assertFalse($agreeTermsField->getConfig()->getOption('mapped'));
        $this->assertInstanceOf(IsTrue::class, $agreeTermsField->getConfig()->getOption('constraints')[0]);

        // Test the constraints on the plainPassword field
        $plainPasswordField = $form->get('plainPassword');
        $this->assertTrue($plainPasswordField->isRequired());
        $this->assertEquals('Password', $plainPasswordField->getConfig()->getOption('attr')['placeholder']);
        $this->assertEquals('bg-transparent block mt-10 mx-auto border-b-2 w-1/5 h-20 text-2xl outline-none', $plainPasswordField->getConfig()->getOption('attr')['class']);
        $this->assertInstanceOf(NotBlank::class, $plainPasswordField->getConfig()->getOption('constraints')[0]);
        $this->assertInstanceOf(Length::class, $plainPasswordField->getConfig()->getOption('constraints')[1]);
    }
}
