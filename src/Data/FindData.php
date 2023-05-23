<?php


namespace App\Data;


use App\Entity\Campus;
use App\Entity\User;

class FindData
{
    /**
     * @var Campus|null
     */
    public ?Campus $campusToSearchTravel = null;

    /**
     * @var null|string
     */
    public ?string $travelByName = '';

    /**
     * @var User
     */
    public User $userConnected;


    public function setUserConnected(User $user): void
    {
        $this->userConnected = $user;
    }

    /**
     * @var bool
     */
    public bool $leaderTravel = false;

    /**
     * @var bool
     */
    public bool $travelsSubscripted = false;

    /**
     * @var bool
     */
    public bool $travelsNotSubscripted = false;

    /**
     * @var null|integer
     */
    public ?int $statusId=null;

//    /**
//     * @var null
//     */
//    public $searchDateStart;
//
//    /**
//     * @var null
//     */
//    public $searchDateFin;


}