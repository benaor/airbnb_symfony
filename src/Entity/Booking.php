<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\PrePersist;
use App\Repository\BookingRepository;

/**
 * @ORM\Entity(repositoryClass=BookingRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Booking
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $booker;

    /**
     * @ORM\ManyToOne(targetEntity=Ad::class, inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ad;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $endDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * Assigns the creation date and the total amount of the reservation
     * 
     * @ORM\PrePersist
     */
    public function prepersist()
    {
        if (empty($this->createdAt)) {
            $this->createdAt = new \DateTime();
        }

        if (empty($this->amount)) {
            $this->amount = $this->ad->getPrice() * $this->getDuration();
        }
    }

    /**
     * indicates if the date is available
     *
     * @return boolean
     */
    public function isBookabledate()
    {
        $notAvailableDays = $this->ad->getNotAvailableDays();
        $bookingDays      = $this->getDays();

        $formatDay = function ($day) {
            return $day->format('Y-m-d');
        };

        $days         = array_map($formatDay, $bookingDays);
        $notAvailable = array_map($formatDay, $notAvailableDays);

        foreach ($days as $day){
            if (array_search($day, $notAvailable) !== false) {
                return false;
            }
        }

        return true;
    }

    /**
     * retrieve the days corresponding to the reservation
     * @return array an array object DateTime represent they days of reservation
     */
    public function getDays()
    {

        $startDaysTimestamp = $this->startDate->getTimestamp();
        $endDaysTimestamp   = $this->endDate->getTimestamp();
        $daysOnMilliseconds = 24 * 60 * 60;

        $resultat = range($startDaysTimestamp, $endDaysTimestamp, $daysOnMilliseconds);

        $days = array_map(function ($daysTimestamp) {
            return new \DateTime(date('Y-m-d', $daysTimestamp));
        }, $resultat);

        return $days;
    }

    /**
     * calculation of the reservation duration (in days)
     */
    public function getDuration()
    {
        $dateDiff = $this->endDate->diff($this->startDate);
        return $dateDiff->days;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBooker(): ?User
    {
        return $this->booker;
    }

    public function setBooker(?User $booker): self
    {
        $this->booker = $booker;

        return $this;
    }

    public function getAd(): ?Ad
    {
        return $this->ad;
    }

    public function setAd(?Ad $ad): self
    {
        $this->ad = $ad;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }
}
