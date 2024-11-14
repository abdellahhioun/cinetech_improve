<?php
namespace App\Controllers;

use App\Models\TVShow;
use Exception;

class TVShowController {
    private $tvShowModel;

    public function __construct() {
        $this->tvShowModel = new TVShow();
    }

    public function showTVShows() {
        // Display a list of TV shows
        $tvShows = $this->tvShowModel->getPopularTVShows();
        require __DIR__ . '/../Views/tvshows.php';
    }

    public function details($id) {
        // Display details of a specific TV show
        $details = $this->tvShowModel->getTVShowDetails($id);
        require __DIR__ . '/../Views/tvshowDetails.php';
    }
}
