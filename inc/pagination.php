<?php
class Pagination
{
    private $page = 1;
    private $perPage = 20;
    private $showFirstAndLast = false;
    private $totalPages;
    private $queryURL = '';

    public function generate($array, $perPage = 20)
    {
        $this->perPage = $perPage;
        $this->totalPages = ceil(count($array) / $this->perPage);

        if (!empty($_GET['pid'])) {
            $this->page = $_GET['pid'];
        } else {
            $this->page = 1;
        }

        $this->start = ($this->page - 1) * $this->perPage;
        return array_slice($array, $this->start, $this->perPage, true);
    }

    public function links()
    {
        if ($this->totalPages <= 1) {
            return '';
        }

        $links = '';
        $currentPageClass = 'active';
        $prevPageLink = '';
        $nextPageLink = '';

        if (count($_GET)) {
            foreach ($_GET as $key => $value) {
                if ($key != 'pid') {
                    $this->queryURL .= '&' . $key . '=' . $value;
                }
            }
        }

        if ($this->showFirstAndLast) {
            if ($this->page > 1) {
                $prevPageLink = '<a href="?pid=' . ($this->page - 1) . $this->queryURL . '" class="arrow left"><i class="las la-angle-left"></i></a>';
            }
            if ($this->page < $this->totalPages) {
                $nextPageLink = '<a href="?pid=' . ($this->page + 1) . $this->queryURL . '" class="arrow right active"><i class="las la-angle-right"></i></a>';
            }
        }

        $links .= '<div class="pagination-blk d-flex align-items-center ml-80">';
        $links .= '<span class="mr-2 md" style="min-width: 80px;">Page <span class="start-page">' . $this->page . '</span> of <span class="end-page">' . $this->totalPages . '</span></span>';
        $links .= '<div class="pagination-arrows">';
        $links .= $prevPageLink;
        $links .= '<a href="?pid=' . max($this->page - 1, 1) . $this->queryURL . '" class="arrow left"><i class="las la-angle-left"></i></a>';

      $links .= '<a href="?pid=' . min($this->page + 1, $this->totalPages) . $this->queryURL . '" class="arrow right active"><i class="las la-angle-right"></i></a>';
        $links .= $nextPageLink;
        $links .= '</div></div>';
        return $links;
    }
}
 