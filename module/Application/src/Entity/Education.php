<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="education")
 */
class Education
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    // Gettery i settery...
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function setName($name) { $this->name = $name; }
} 