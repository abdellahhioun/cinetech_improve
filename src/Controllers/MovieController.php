<?php

namespace Ahiou\CinetechImprove\Controllers;

use Ahiou\CinetechImprove\Database\Database;

class MovieController {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    private function render($view, $data = []) {
        extract($data);
        require_once __DIR__ . '/../Views/layout/header.php';
        require_once __DIR__ . '/../Views/'. $view . '.php';
        require_once __DIR__ . '/../Views/layout/footer.php';
    }

    public function index() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $movies = $this->getPopularMovies($page);
        $this->render('movies/popular', [
            'movies' => $movies,
            'currentPage' => $page
        ]);
    }

    public function search() {
        $query = isset($_GET['query']) ? $_GET['query'] : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        
        if ($query) {
            $results = $this->searchMovies($query, $page);
            $this->render('movies/search', [
                'movies' => $results,
                'query' => $query,
                'currentPage' => $page
            ]);
        } else {
            $this->render('movies/search', [
                'movies' => ['results' => []],
                'query' => '',
                'currentPage' => 1
            ]);
        }
    }

    public function details($id) {
        $movie = $this->getMovieDetails($id);
        $currentReviewPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $reviews = $this->getMovieReviews($id, $currentReviewPage);
        $isFavorite = $this->isFavorite($id);
        
        $this->render('movies/details', [
            'movie' => $movie,
            'reviews' => $reviews,
            'currentReviewPage' => $currentReviewPage,
            'isFavorite' => $isFavorite
        ]);
    }

    public function getPopularMovies($page = 1) {
        $url = TMDB_API_BASE_URL . '/movie/popular';
        $params = [
            'api_key' => TMDB_API_KEY,
            'page' => $page
        ];
        
        $url .= '?' . http_build_query($params);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($response, true);
    }

    public function searchMovies($query, $page = 1) {
        $url = TMDB_API_BASE_URL . '/search/movie';
        $params = [
            'api_key' => TMDB_API_KEY,
            'query' => $query,
            'page' => $page
        ];
        
        $url .= '?' . http_build_query($params);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($response, true);
    }

    public function getMovieDetails($movieId) {
        $url = TMDB_API_BASE_URL . '/movie/' . $movieId;
        $params = [
            'api_key' => TMDB_API_KEY,
            'append_to_response' => 'credits,videos'
        ];
        
        $url .= '?' . http_build_query($params);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($response, true);
    }

    public function getMovieReviews($movieId, $page = 1) {
        $url = TMDB_API_BASE_URL . '/movie/' . $movieId . '/reviews';
        $params = [
            'api_key' => TMDB_API_KEY,
            'page' => $page
        ];
        
        $url .= '?' . http_build_query($params);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($response, true);
    }

    public function toggleFavorite($movieId) {
        if (!isset($_SESSION['user_id'])) {
            return ['success' => false, 'message' => 'Please login first'];
        }

        $userId = $_SESSION['user_id'];
        
        // Check if movie exists in movies table, if not add it
        $stmt = $this->db->prepare("SELECT id FROM movies WHERE tmdb_id = ?");
        $stmt->execute([$movieId]);
        $movie = $stmt->fetch();
        
        if (!$movie) {
            // Fetch movie details from API
            $movieData = $this->getMovieDetails($movieId);
            
            // Insert movie into database
            $stmt = $this->db->prepare("INSERT INTO movies (tmdb_id, title, poster_path, overview) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $movieData['id'],
                $movieData['title'],
                $movieData['poster_path'],
                $movieData['overview']
            ]);
            $movieDbId = $this->db->lastInsertId();
        } else {
            $movieDbId = $movie['id'];
        }
        
        // Check if already favorite
        $stmt = $this->db->prepare("SELECT id FROM favorites WHERE user_id = ? AND movie_id = ?");
        $stmt->execute([$userId, $movieDbId]);
        $favorite = $stmt->fetch();
        
        if ($favorite) {
            // Remove from favorites
            $stmt = $this->db->prepare("DELETE FROM favorites WHERE user_id = ? AND movie_id = ?");
            $stmt->execute([$userId, $movieDbId]);
            return ['success' => true, 'action' => 'removed'];
        } else {
            // Add to favorites
            $stmt = $this->db->prepare("INSERT INTO favorites (user_id, movie_id) VALUES (?, ?)");
            $stmt->execute([$userId, $movieDbId]);
            return ['success' => true, 'action' => 'added'];
        }
    }

    public function isFavorite($movieId) {
        if (!isset($_SESSION['user_id'])) {
            return false;
        }

        $stmt = $this->db->prepare("
            SELECT f.id 
            FROM favorites f 
            JOIN movies m ON f.movie_id = m.id 
            WHERE f.user_id = ? AND m.tmdb_id = ?
        ");
        $stmt->execute([$_SESSION['user_id'], $movieId]);
        return $stmt->fetch() ? true : false;
    }

    public function favorites() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $stmt = $this->db->prepare("
            SELECT m.* 
            FROM movies m 
            JOIN favorites f ON m.id = f.movie_id 
            WHERE f.user_id = ? 
            ORDER BY f.created_at DESC
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $favorites = $stmt->fetchAll();

        $this->render('movies/favorites', ['favorites' => $favorites]);
    }
} 