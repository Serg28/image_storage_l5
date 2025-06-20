<?php namespace Linecore\ImageStorage;

class YoutubeAPI extends AbstractVideoAPI
{
    protected $configPrefix = 'video_api.providers.youtube';

    public function videoExists()
    {
        try {
            $url = $this->getConfigAPIExistenceUrl();
            $queryParams = [
                'format' => 'json',
                'url'    => $this->getWatchUrl()
            ];
            
            $response = $this->httpClient()->get($url, ['query' => $queryParams]);
            
            return $response->getStatusCode() === 200;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getPreviewUrl()
    {
        $stubs = ["{id}", "{quality}"];

        $replacements = [$this->getVideoId(), $this->getConfigAPIPreviewQuality()];

        $url = str_replace($stubs, $replacements, $this->getConfigAPIPreviewUrl());

        return $url;
    }

    public function requestApiData()
    {
        try {
            $queryParams = [
                'id'    => $this->getVideoId(),
                'part'  => $this->getConfigAPIParts(),
                'key'   => $this->getConfigAPIKey()
            ];
            
            $response = $this->httpClient()->get($this->getConfigAPIURL(), ['query' => $queryParams]);
            
            if ($response->getStatusCode() !== 200) {
                return false;
            }
            
            $apiData = json_decode($response->getBody()->getContents());
            
            return array_shift($apiData->items);
        } catch (\Exception $e) {
            return false;
        }
    }

    //todo rewrite to ??(coalesce) operator
    public function getTitle()
    {
        return isset($this->getApiResponse()->snippet->title) ? $this->getApiResponse()->snippet->title : "";
    }

    public function getDescription()
    {
        return isset($this->getApiResponse()->snippet->description) ? $this->getApiResponse()->snippet->description : "";
    }

    public function getViewCount()
    {
        return isset($this->getApiResponse()->statistics->viewCount) ? $this->getApiResponse()->statistics->viewCount : 0;
    }

    public function getLikeCount()
    {
        return isset($this->getApiResponse()->statistics->likeCount) ? $this->getApiResponse()->statistics->likeCount : 0;
    }

    public function getDislikeCount()
    {
        return isset($this->getApiResponse()->statistics->dislikeCount) ? $this->getApiResponse()->statistics->dislikeCount : 0;
    }

    public function getFavoriteCount()
    {
        return isset($this->getApiResponse()->statistics->favoriteCount) ? $this->getApiResponse()->statistics->favoriteCount : 0;
    }

    public function getCommentCount()
    {
        return isset($this->getApiResponse()->statistics->commentCount) ? $this->getApiResponse()->statistics->commentCount : 0;
    }
}
