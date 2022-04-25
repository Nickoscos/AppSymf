<?php

namespace App\Entity;

use App\Entity\Listing;
use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints As Assert;

/**
 * @ORM\Entity(repositoryClass=TaskRepository::class)
 */
class Task
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Listing", inversedBy="tasks")
     * @ORM\JoinColumn()
     */
    private $listing;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dueDate; 

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Assert\Range(
     *  min = 5,
     *  max =300,
     *  minMessage = "Vous devez configurer le rappel au moins 5min avant la tâche d'échéance de la tâche",
     *  maxMessage = "Vous devez configurer le rappel au plus 300min avant la tâche d'échéance de la tâche"
     * )
     */
    private $remender;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return mixed
     */

    public function getListing()
    {
        return $this->listing;
    }

    /**
     * @param mixed $listing
     */

    public function setListing(Listing $listing) : void
    {
        $this->listing = $listing;
    }

    /**
     * @return mixed
     */
    public function getDueDate()
    {
        return $this->dueDate;
    }

    /**
     * @param mixed $dueDate
     */
    public function setDueDate($dueDate) : void
    {
        $this->dueDate = $dueDate;
    }

    /**
     * @@return mixed
     */
    public function getRemender()
    {
        return $this->remender;
    }

    /**
     * @param mixed $remender
     */
    public function setRemender($remender) : void
    {
        $this->remender = $remender;
    }


}
