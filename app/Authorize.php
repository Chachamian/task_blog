<?php

namespace App;
use App\Mapper\Subscribe;
use App\Mapper\User;

class Authorize
{
    private FileSystem $_fileSystem;
    private ?string $_username;
    private ?string $_password;
    private ?string $_email;
    private array $_errors;

    public function __construct(?string $username = null, ?string $email = null, ?string $password = null)
    {
        $this->_username  = $username;
        $this->_email     = $email;
        $this->_password  = $password;
        $this->_fileSystem = new FileSystem();
        $this->_errors    = [];
    }

    public function getSubscribesForUser($userId): array
    {
        $user = $this->getUserById($userId);

        if ($user === null) {
            return [];
        }

        $subscribes = $this->_fileSystem->getSubscribesFileData();
        $result     = [];

        foreach ($subscribes as $subscribe) {
            if ($subscribe->ownerId === $user->id) {
                $result[] = $subscribe;
            }
        }

        return $result;
    }

    public function isSubscribeCurrentUser($ownerId, $userId = null): bool
    {
        if ($ownerId === null) {
            return false;
        }

        $user = $userId == null ? $this->user() : $this->getUserById($userId);
        $owner = $this->getUserById($ownerId);

        if ($owner === null || $user === null) {
            return false;
        }

        $subscribes = $this->_fileSystem->getSubscribesFileData();

        foreach ($subscribes as $key => $subscribe) {
            if ($subscribe->userId == $user->id && $subscribe->ownerId == $ownerId) {
                return true;
            }
        }

        return false;
    }

    public function subscribe($ownerId, $userId = null)
    {
        $this->_errors = [];
        if ($ownerId === null) {
            $this->_errors['subscribe'] = 'Не передан пользователь на которого вы хотите подписаться';
            return;
        }

        $user = $userId == null ? $this->user() : $this->getUserById($userId);
        $owner = $this->getUserById($ownerId);

        if ($owner === null || $user === null) {
            $this->_errors['subscribe'] = 'Такой пользователь не найден';
            return;
        }

        $subscribes = $this->_fileSystem->getSubscribesFileData();

        foreach ($subscribes as $key => $subscribe) {
            if ($subscribe->userId == $user->id && $subscribe->ownerId == $ownerId) {
                unset($subscribes[$key]);
                $this->_fileSystem->writeSubscribesFileData($subscribes, true);
                return;
            }
        }

        $subscribes[] = new Subscribe(
            $this->_fileSystem->GetIncrement('subscribes'),
            $user->id,
            $ownerId
        );

        $this->_fileSystem->writeSubscribesFileData($subscribes);
    }

    public function auth()
    {
        $this->clearError();
        $users = $this->_fileSystem->getUsersFileData();
        try {
            $this->_checkAuthData();
        } catch (\Exception $e) {
            return;
        }

        foreach ($users as $user) {
            if ($user->userName === $this->_username && password_verify($this->_password, $user->password)) {
                $_SESSION['username'] = $this->_username;
                header('location: /');
            }
        }
        $this->_errors['username'] = 'Проверьте учетные данные';
    }

    public function register()
    {
        $this->clearError();
        $users = $this->_fileSystem->getUsersFileData();
        try {
            $this->_checkAuthData(true);
        } catch (\Exception $e) {
            $this->_errors[] = $e->getMessage();
            return;
        }

        foreach ($users as $user) {
            if ($user->userName === $this->_username) {
                $this->_errors['username'] = "Пользователь с таким именем уже существует!";
                return;
            }

            if ($user->email === $this->_email) {
                $this->_errors['email'] = "Пользователь с таким e-mail уже существует!";
                return;
            }
        }

        $hashedPassword = password_hash($this->_password, PASSWORD_DEFAULT);
        $users[] = new User(
            $this->_fileSystem->GetIncrement('users'),
            $this->_username,
            $this->_email,
            $hashedPassword,
            0,
        );

        $this->_fileSystem->writeUsersFileData($users);
        $_SESSION['username'] = $this->_username;
        header('location: /');
    }

    public function isAdmin(): bool
    {
        $user = $this->user();

        if ($user !== null && $user->role === 1) {
            return true;
        }

        return false;
    }

    public function user(): ?User
    {
        if (!empty($_SESSION['username'])) {
            $users = $this->_fileSystem->getUsersFileData();
            foreach ($users as $user) {
                if ($_SESSION['username'] === $user->userName) {
                    return new User(
                        $user->id,
                        $user->userName,
                        $user->email,
                        null,
                        $user->role
                    );
                }
            }
        }

        return null;
    }

    public function getUserById(?int $id): ?User
    {
        if ($id === null) {
            return null;
        }

        $users = $this->_fileSystem->getUsersFileData();
        foreach ($users as $user) {
            if ($id === $user->id) {
                return new User(
                    $user->id,
                    $user->userName,
                    $user->email,
                    null,
                    $user->role
                );
            }
        }

        return null;
    }

    public function getError(): array
    {
        return $this->_errors;
    }

    public function clearError(): void
    {
        $this->_errors = [];
    }

    public function clearSession(): void
    {
        session_destroy();
    }

    private function _checkAuthData(bool $isRegister = false)
    {
        $errors = false;

        if (empty($this->_username)) {
            $this->_errors['password'] = "Поле имя обязательно для заполнения";
            $errors = true;
        }

        if (empty($this->_password)) {
            $this->_errors['username'] = "Поле пароль обязательно для заполнения";
            $errors = true;
        }

        if (empty($this->_email) && $isRegister) {
            $this->_errors['email'] = "Поле email обязательно для заполнения";
            $errors = true;
        }

        if ($isRegister && !filter_var($this->_email, FILTER_VALIDATE_EMAIL)) {
            $this->_errors['email'] = "Email введен некорректно";
            $errors = true;
        }

        if ($errors) {
            throw new \App\Exception\Authorize();
        }
    }
}