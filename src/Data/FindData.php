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
         * @var User|null
         */
        public ?User $userConnected;
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
         * @var boolean
         */
        public ?bool $statusId = false;
        /**
         * @var null
         */
        public $searchDateStart;
        /**
         * @var null
         */
        public $searchDateFin;

        public function setUserConnected(User|null $user): void
        {
            $this->userConnected = $user;
        }


    }