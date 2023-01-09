<?php

declare(strict_types=1);

namespace App\Module\Presenters;

use App\Model\ProblemFacade;
use Nette;
use App\Model\UserFacade;
use Nette\Application\UI\Form;


final class GamePresenter extends Nette\Application\UI\Presenter{
    private ProblemFacade $problemFacade;
    private $problem;

    public function __construct(ProblemFacade $problemFacade){
        $this->problemFacade = $problemFacade;
    }

    public function renderDefault(){
        if(!$this->user->isLoggedIn()){
            $this->flashMessage("Nejprve se prosím přihlaště");
            $this->redirect("Sign:in");
        }
    }

    public function renderPlay($difficulty){
        if(!$this->user->isLoggedIn()){
            $this->flashMessage("Nejprve se prosím přihlaště");
            $this->redirect("Sign:in");
        }

        switch($difficulty){
            case "easy":
                $this->problemFacade->getByDifficulty($difficulty);
                $this->template->problem = "aaa";
                break;
            case "medium":
                $this->problemFacade->getByDifficulty($difficulty);
                $this->template->problem = "aaa";
                break;
            case "hard":
                $this->problemFacade->getByDifficulty($difficulty);
                $this->template->problem = "aaa";
                break;
        }
    
    }


}