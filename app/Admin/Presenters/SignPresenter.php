<?php
namespace App\Module\Presenters;

use Nette;
use App\Model\UserFacade;
use Nette\Application\UI\Form;

final class SignPresenter extends Nette\Application\UI\Presenter
{
	private UserFacade $userFacade;

	public function __construct(UserFacade $userFacade)
	{
		$this->userFacade = $userFacade;
	}

	protected function createComponentSignInForm(): Form
	{
		$form = new Form;
		$form->addText('username', 'Uživatelské jméno:')
			->setRequired('Prosím vyplňte své uživatelské jméno.');

		$form->addPassword('password', 'Heslo:')
			->setRequired('Prosím vyplňte své heslo.');

		$form->addSubmit('send', 'Přihlásit');

		$form->onSuccess[] = [$this, 'signInFormSucceeded'];
		return $form;
	}
    public function signInFormSucceeded(Form $form, \stdClass $data): void
{
	try {
		$this->getUser()->login($data->username, $data->password);
		$this->redirect('Homepage:');

	} catch (Nette\Security\AuthenticationException $e) {
		$form->addError('Nesprávné přihlašovací jméno nebo heslo.');
	}
}
public function actionOut(): void
{
	$this->getUser()->logout();
	$this->flashMessage('Odhlášení bylo úspěšné.');
	$this->redirect('Homepage:');
}
public function createComponentRegisterForm(): Form
{
	$form = new Form;
	$form->addText('username', 'Uživatelské jméno:')
		->setRequired('Prosím vyplňte své uživatelské jméno.');
	$form->addPassword('password', 'Heslo:')
		->setRequired('Prosím vyplňte své heslo.');
	$form->addEmail('email', 'E-mail:')
		->setRequired('Prosím vyplňte svůj e-mail.');
	
	$form->addSubmit('send', 'Registrovat');
	$form->onSuccess[] = [$this, 'onRegisterSuccess'];
	return $form;
}
public function onRegisterSuccess(Form $form, \stdClass $data)
{
	$this->userFacade->add($data->username, $data->email, $data->password);

	$this->flashMessage('Registrace byla úspěšná.');
	$this->redirect('Homepage:');
}
public function createComponentChangeForm(): Form
{
	$user = $this->getUser()->getIdentity();
	$form = new Form;
	$form->addText('username', 'Uživatelské jméno:')
	->setDefaultValue($user->data['username'])
		->setRequired('Prosím vyplňte své uživatelské jméno.');
		$form->addText('email', 'E-mail:')
		->setDefaultValue($user->data['email'])
		->setRequired('Prosím zadejte nový e-mail.');
		$form->addPassword('password', 'Heslo:');

	$form->addSubmit('send', 'Změnit');
	$form->onSuccess[] = [$this, 'ChangeFormSucceded'];
	return $form;
}
public function ChangeFormSucceded(Form $form, \stdClass $data)
{
	$this->userFacade->update($this->getUser()->getId(), $data);
	$this->getUser()->logout();
	$this->flashMessage('Změna byla úspěšná. Prosím přihlaště se znovu.', 'success');
	$this->redirect('Homepage:');
}
}