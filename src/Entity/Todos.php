<?php

namespace App\Entity;

use App\Repository\TodosRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TodosRepository::class)]
class Todos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TodosList $todosList = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getTodosList(): ?TodosList
    {
        return $this->todosList;
    }

    public function setTodosList(?TodosList $todosList): self
    {
        $this->todosList = $todosList;
        return $this;
    }
}
