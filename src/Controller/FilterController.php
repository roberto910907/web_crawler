<?php

namespace App\Controller;

use App\Client\Client;
use App\Model\FilterModel;
use App\Service\FilterService;
use App\Session\Interfaces\SessionInterface;

class FilterController
{
    private $session;

    /**
     * Constructor.
     *
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * Render the filter table view.
     *
     * @param FilterModel $filterModel
     *
     * @return string
     */
    public function renderFilterTable(FilterModel $filterModel = null)
    {
        $filter = new FilterService(new Client());

        $this->session->set(FilterModel::ROWS, $filter->applyFilters($filterModel));

        return include __DIR__ . '/../View/validation_view.php';
    }
}
