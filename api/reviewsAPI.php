<?php

class ReviewsAPI
{
    private $urlBase;
    private $headers;

    public function __construct($urlBase)
    {
        $this->urlBase = $urlBase;
        $this->headers = [
            'Content-Type: application/json',
            'Accept: application/json'
        ];
    }

    public function setAuthToken($token)
    {
        $this->headers[] = "Authorization: Bearer $token";
    }

    // GET ENDPOINTS

    public function getReviewsByUser($user)
    {
        return $this->sendRequest('GET', "/reviews/user/" . urlencode($user));
    }

    public function getStatsByUser($user) {
        return $this->sendRequest('GET', "/reviews/" . urlencode($user) . "/stats");
    }

    public function getFavMoviesByUser($user)
    {
        return $this->sendRequest('GET', "/reviews/$user/favorites");
    }

    public function getWorstMoviesByUser($user)
    {
        return $this->sendRequest('GET', "/reviews/$user/leastfavorites");
    }

    public function getReviewById($id)
    {
        return $this->sendRequest('GET', "/reviews/id/$id");
    }

    public function getMostUpvoted($user)
    {
        return $this->sendRequest('GET', "/reviews/$user/mostupvoted");
    }

    public function getNoSpoilers($user)
    {
        return $this->sendRequest('GET', "/reviews/$user/nospoilers");
    }

    public function getReportedReviews($user)
    {
        return $this->sendRequest('GET', "/reviews/$user/reported");
    }

    // POST ENDPOINTS

    public function createReview($data)
    {
        return $this->sendRequest('POST', '/reviews/create', $data);
    }

    public function upvoteReview($id)
    {
        return $this->sendRequest('POST', "/reviews/upvote/$id");
    }

    public function reportReview($id)
    {
        return $this->sendRequest('POST', "/reviews/report/$id");
    }

    // PUT/PATCH ENDPOINTS

    public function updateReview($id, $data)
    {
        return $this->sendRequest('PUT', "/reviews/modify/$id", $data);
    }

    public function updateRating($id, $rating)
    {
        return $this->sendRequest('PATCH', "/reviews/modify/$id/rating", ['rating' => $rating]);
    }

    public function updateComment($id, $comment)
    {
        return $this->sendRequest('PATCH', "/reviews/modify/$id/comment", ['comment' => $comment]);
    }

    public function updateTitle($id, $title)
    {
        return $this->sendRequest('PATCH', "/reviews/modify/$id/title", ['title' => $title]);
    }

    // DELETE ENDPOINTS

    public function deleteReview($id)
    {
        return $this->sendRequest('DELETE', "/reviews/remove/$id");
    }

    public function removeAllReviews($user)
    {
        return $this->sendRequest('DELETE', "/reviews/remove/$user/all");
    }

    public function removeBadMovieReviews($user)
    {
        return $this->sendRequest('DELETE', "/reviews/remove/$user/badmovies");
    }

    public function removeLowUpvotedReviews($user, $count)
    {
        return $this->sendRequest('DELETE', "/reviews/remove/$user/lowupvotes/$count");
    }

    public function deleteReportedReviews()
    {
        return $this->sendRequest('DELETE', '/reviews/remove/reported');
    }

    private function sendRequest($method, $endpoint, $data = null) {
        $url = $this->urlBase . $endpoint;
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $this->headers,
        ]);

        if ($data !== null && in_array($method, ['POST', 'PUT', 'PATCH'])) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        // Manejar respuesta como texto plano o JSON
        $decoded = json_decode($response, true);
        
        return [
            'status' => $httpCode,
            'data' => ($decoded === null) ? $response : $decoded
        ];
    }
}
