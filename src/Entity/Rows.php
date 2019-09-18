<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RowsRepository")
 */
class Rows
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product")
     * @ORM\JoinColumn(nullable=false)
     */
    private $P;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Invoice")
     * @ORM\JoinColumn(nullable=false)
     */
    private $I;

    /**
     * @ORM\Column(type="float")
     */
    private $rows;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getP(): ?Product
    {
        return $this->P;
    }

    public function setP(?Product $P): self
    {
        $this->P = $P;

        return $this;
    }

    public function getI(): ?Invoice
    {
        return $this->I;
    }

    public function setI(?Invoice $I): self
    {
        $this->I = $I;

        return $this;
    }

    public function getRows(): ?float
    {
        return $this->rows;
    }

    public function setRows(float $rows): self
    {
        $this->rows = $rows;

        return $this;
    }
}
