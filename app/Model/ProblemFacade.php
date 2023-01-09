<?php

declare(strict_types=1);

namespace App\Model;

use Nette;
use Nette\Security\Passwords;
use Nette\Utils\Validators;

/**
 * Users management.
 */
final class ProblemFacade{

	use Nette\SmartObject;

	private const
		Table_Name = 'problems',
		Column_Id = 'id',
		Column_Problem = 'problem',
		Column_Answer = 'answer',
		Column_Difficulty = 'difficulty';

	private Nette\Database\Explorer $database;

	public function __construct(Nette\Database\Explorer $database){
		$this->database = $database;
	}

    public function getByDifficulty($difficulty){
        $data = $this->database->table(SELF::Table_Name)->select("*")->where(SELF::Column_Difficulty, $difficulty);
        
        return $data;
    }

}