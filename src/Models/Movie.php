<?php

namespace Ahiou\CinetechImprove\Models;

class Movie {
    private $id;
    private $title;
    private $tmdb_id;
    private $poster_path;
    private $overview;

    public function __construct($data = []) {
        if ($data) {
            $this->hydrate($data);
        }
    }

    public function hydrate($data) {
        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    // Getters
    public function getId() { return $this->id; }
    public function getTitle() { return $this->title; }
    public function getTmdbId() { return $this->tmdb_id; }
    public function getPosterPath() { return $this->poster_path; }
    public function getOverview() { return $this->overview; }

    // Setters
    public function setId($id) { $this->id = $id; }
    public function setTitle($title) { $this->title = $title; }
    public function setTmdbId($tmdb_id) { $this->tmdb_id = $tmdb_id; }
    public function setPosterPath($poster_path) { $this->poster_path = $poster_path; }
    public function setOverview($overview) { $this->overview = $overview; }
} 