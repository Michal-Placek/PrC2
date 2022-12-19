<?php

declare(strict_types=1);

namespace App\Module\Presenters;

use Nette;
use App\Model\UserFacade;
use Nette\Application\UI\Form;


final class GamePresenter extends Nette\Application\UI\Presenter{

    private $problem;

    public function __construct(){
    }

    public function renderDefault(){
        if(!$this->user->isLoggedIn()){
            $this->flashMessage("musíte být přihlášen");
            $this->redirect("Sign:in");
        }
    }

    public function renderBabymode(){
        if(!$this->user->isLoggedIn()){
            $this->flashMessage("musíte být přihlášen");
            $this->redirect("Sign:in");
        }

        $this->template->problem = "aaa";
    
    }

    protected function createComponentBabymode(): Form
	{
		$form = new Form;


		$form->addText('answer', 'odpověď')
			->setRequired('Prosím vyplňte odpověď.');

		$form->addSubmit('send', 'Přihlásit');

		$form->onSuccess[] = [$this, 'BabymodeSucceeded',  $pronlem];
		return $form;
	}

    public function BabymodeSucceeded(Form $form, \stdClass $data, $problem): void
    {
        bdump($data->answer . $problem);
        if($data->answer == $problem){
            bdump("sex");
        }else{
            bdump("nosex");
        }

    }
//----------------------------
    protected function createComponentMidway(): Form
	{
		$form = new Form;

		$form->addText('answer', 'odpověď')
			->setRequired('Prosím vyplňte odpověď.');

		$form->addSubmit('send', 'Přihlásit');

		$form->onSuccess[] = [$this, 'MidwaySucceeded'];
		return $form;
	}

    public function MidwaySucceeded(Form $form, \stdClass $data): void
    {

    }
//----------------------------
    protected function createComponentHell(): Form
	{
		$form = new Form;


		$form->addText('answer', 'odpověď')
			->setRequired('Prosím vyplňte odpověď.');

		$form->addSubmit('send', 'Přihlásit');

		$form->onSuccess[] = [$this, 'HellSucceeded'];
		return $form;
	}

    public function HellSucceeded(Form $form, \stdClass $data): void
    {

    }
//----------------------------
    protected function createComponentHard(): Form
	{
		$form = new Form;


		$form->addText('answer', 'odpověď')
			->setRequired('Prosím vyplňte odpověď.');

		$form->addSubmit('send', 'Přihlásit');

		$form->onSuccess[] = [$this, 'HardSucceeded'];
		return $form;
	}

    public function HardSucceeded(Form $form, \stdClass $data): void
    {

    }
}