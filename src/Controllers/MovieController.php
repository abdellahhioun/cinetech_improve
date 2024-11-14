<?php
namespace App\Controllers;

use App\Models\Movie;
use Exception;

class MovieController {
    private $movieModel;

    public function __construct() {
        $this->movieModel = new Movie();
    }

    public function index() {
        // Display some movies on the homepage
        $movies = $this->movieModel->getPopularMovies();
        require __DIR__ . '/../Views/home.php';
    }

    public function showMovies() {
        // Display a list of movies
        $movies = $this->movieModel->getPopularMovies();
        require __DIR__ . '/../Views/movies.php';
    }

    public function details($id) {
        // Display details of a specific movie
        $details = $this->movieModel->getMovieDetails($id);
        require __DIR__ . '/../Views/movieDetails.php';
    }
}
