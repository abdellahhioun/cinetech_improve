<?php
namespace App\Models;

class TVShow {
    public function getPopularTVShows() {
        $url = TMDB_API_BASE_URL . '/tv/popular?api_key=' . TMDB_API_KEY;
        return $this->fetchData($url);
    }

    public function getTVShowDetails($id) {
        $url = TMDB_API_BASE_URL . "/tv/{$id}?api_key=" . TMDB_API_KEY . '&append_to_response=credits';
        return $this->fetchData($url);
    }

    private function fetchData($url) {
        $response = file_get_contents($url);
        return $response ? json_decode($response, true) : [];
    }
}
