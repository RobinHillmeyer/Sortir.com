<?php

namespace App\Data;

use App\Entity\Campus;

class SearchData
{
    /**
     * @var string
     */
    public $keyWord = '';

    /**
     * @var Campus
     */
    public $campus;

    /**
     * @var \DateTime
     */
    public $dateFrom;

    /**
     * @var \DateTime
     */
    public $dateTo;

    /**
     * @var boolean
     */
    public $isPromoter;

    /**
     * @var boolean
     */
    public $isSub;

    /**
     * @var boolean
     */
    public $isntSub;

    /**
     * @var boolean
     */
    public $isTripEnd;

}