<?php
namespace App\Models;

class Movie {
    public function getPopularMovies() {
        $url = TMDB_API_BASE_URL . '/movie/popular?api_key=' . TMDB_API_KEY;
        return $this->fetchData($url);
    }

    public function getMovieDetails($id) {
        $url = TMDB_API_BASE_URL . "/movie/{$id}?api_key=" . TMDB_API_KEY . '&append_to_response=credits';
        return $this->fetchData($url);
    }

    private function fetchData($url) {
        $response = file_get_contents($url);
        return $response ? json_decode($response, true) : [];
    }
}
