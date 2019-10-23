<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="customer")
 * @ORM\Entity(repositoryClass="App\Repository\CustomerEntityRepository")
 */
class CustomerEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20, name="first_name")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=60, name="password_hash")
     */
    private $passwordHash;

    /**
     * @ORM\Column(type="float", name="account_balance")
     */
    private $accountBalance;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPasswordHash(): ?string
    {
        return $this->passwordHash;
    }

    public function setPasswordHash(string $password_hash): self
    {
        $this->passwordHash = $password_hash;

        return $this;
    }

    public function getAccountBalance(): ?string
    {
        return $this->accountBalance;
    }

    public function setAccountBalance(string $accountBalance): self
    {
        $this->accountBalance = $accountBalance;

        return $this;
    }
}
