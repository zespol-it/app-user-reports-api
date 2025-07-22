<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User
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

    /**
     * @ORM\Column(type="string")
     */
    private $phone_number;

    /**
     * @ORM\Column(type="string")
     */
    private $address;

    /**
     * @ORM\Column(type="integer")
     */
    private $age;

    /**
     * @ORM\ManyToOne(targetEntity="Education")
     * @ORM\JoinColumn(name="education_id", referencedColumnName="id")
     */
    private $education;

    // Gettery i settery...
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function setName($name) { $this->name = $name; }
    public function getPhoneNumber() { return $this->phone_number; }
    public function setPhoneNumber($phone_number) { $this->phone_number = $phone_number; }
    public function getAddress() { return $this->address; }
    public function setAddress($address) { $this->address = $address; }
    public function getAge() { return $this->age; }
    public function setAge($age) { $this->age = $age; }
    public function getEducation() { return $this->education; }
    public function setEducation($education) { $this->education = $education; }
} 