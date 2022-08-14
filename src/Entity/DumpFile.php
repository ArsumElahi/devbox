<?php

namespace App\Entity;

use App\Repository\DumpFileRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DumpFileRepository::class)
 */
class DumpFile
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $service_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $start_date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $method_type;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $status_code;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getServiceName(): ?string
    {
        return $this->service_name;
    }

    public function setServiceName(?string $service_name): self
    {
        $this->service_name = $service_name;

        return $this;
    }

    public function getDateTime():?string
    {
        return $this->start_date;
    }

    public function setDateTime(?string $start_date): self
    {
        $this->start_date = date("Y-m-d h:i:s", strtotime($start_date));

        return $this;
    }

    public function getMethodType(): ?string
    {
        return $this->method_type;
    }

    public function setMethodType(?string $method_type): self
    {
        $this->method_type = $method_type;

        return $this;
    }

    public function getCodeType(): ?int
    {
        return $this->status_code;
    }

    public function setCodeType(?int $status_code): self
    {
        $this->code_type = $status_code;

        return $this;
    }
}
