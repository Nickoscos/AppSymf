<?php

namespace App\Entity;

use App\Repository\ListingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ListingRepository::class)
 */

class Listing
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150, unique=true)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Task", mappedBy="listing")
     * @ORM\JoinColumn()
     */
    private $tasks;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */

    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * @param mixed $tasks
     */

    public function setTasks($tasks) : void
    {
        $this->tasks = $tasks;
    }
}
