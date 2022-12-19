<?php

declare(strict_types=1);

namespace App\Model;

use Nette;
use Nette\Security\Passwords;
use Nette\Utils\Validators;

/**
 * Users management.
 */
final class UserFacade implements Nette\Security\Authenticator
{
	use Nette\SmartObject;

	public const PASSWORD_MIN_LENGTH = 7;

	private const
		Table_Name = 'users',
		Column_Id = 'id',
		Column_Name = 'username',
		Column_Password_Hash = 'password',
		Column_Email = 'email',
		Column_Role = 'role';


	private Nette\Database\Explorer $database;

	private Passwords $passwords;


	public function __construct(Nette\Database\Explorer $database, Passwords $passwords)
	{
		$this->database = $database;
		$this->passwords = $passwords;
	}


	/**
	 * Performs an authentication.
	 * @throws Nette\Security\AuthenticationException
	 */
	public function authenticate(string $username, string $password): Nette\Security\SimpleIdentity
	{
		$row = $this->database->table(self::Table_Name)
			->where(self::Column_Name, $username)
			->fetch();

		if (!$row) {
			throw new Nette\Security\AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);

		} elseif (!$this->passwords->verify($password, $row[self::Column_Password_Hash])) {
			throw new Nette\Security\AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);

		} elseif ($this->passwords->needsRehash($row[self::Column_Password_Hash])) {
			$row->update([
				self::Column_Password_Hash => $this->passwords->hash($password),
			]);
		}

		$arr = $row->toArray();
		unset($arr[self::Column_Password_Hash]);
		return new Nette\Security\SimpleIdentity($row[self::Column_Id], $row[self::Column_Role], $arr);
	}

	public function getAll()
	{
		return $this->database->table('users')->select("*")->fetchAll();
	}
	public function getUserById(int $id)
	{
		return $this->database->table('users')->select("*")->where("id", $id)->fetchAll();
	}

	/**
	 * Adds new user.
	 * @throws DuplicateNameException
	 */
	public function add(string $username, string $email, string $password): void
	{
		Nette\Utils\Validators::assert($email, 'email');
		try {
			$this->database->table(self::Table_Name)->insert([
				self::Column_Name => $username,
				self::Column_Password_Hash => $this->passwords->hash($password),
				self::Column_Email => $email,
			]);
		} catch (Nette\Database\UniqueConstraintViolationException $e) {
			throw new DuplicateNameException;
		}
	}
	public function update(int $userId, \stdClass $data): void
	{
		if ($data->password == null) {
			$this->database->table(self::Table_Name)->get(['id'=>$userId])->update([
				self::Column_Name => $data->username,
				self::Column_Email => $data->email,
			]);}

		else {
			$this->database->table(self::Table_Name)->get(['id'=>$userId])->update([
			self::Column_Name => $data->username,
			self::Column_Email => $data->email,
			self::Column_Password_Hash => $this->passwords->hash($data->password),
		]);}
	}
}




class DuplicateNameException extends \Exception
{
}