<?php namespace Linecore\ImageStorage;

use Illuminate\Support\Facades\Cache;

class VimeoAPI extends AbstractVideoAPI
{
    protected $configPrefix = 'video_api.providers.vimeo';

    public function videoExists()
    {
        try {
            $url = $this->getConfigAPIExistenceUrl();
            $queryParams = ['url' => $this->getWatchUrl()];
            
            $response = $this->httpClient()->get($url, ['query' => $queryParams]);
            
            return $response->getStatusCode() === 200;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getPreviewUrl()
    {
        $tag       = $this->getConfigNamespace() . '.video';
        $cacheName = $this->getConfigPrefix() . "." . $this->getVideoId() . ".preview-id";

        $imageId = Cache::tags($tag)->rememberForever($cacheName, function () {
            try {
                $url = $this->getConfigAPIExistenceUrl();
                $queryParams = ['url' => $this->getWatchUrl()];
                
                $response = $this->httpClient()->get($url, ['query' => $queryParams]);
                
                if ($response->getStatusCode() !== 200) {
                    return false;
                }
                
                $result = json_decode($response->getBody()->getContents());
                
                preg_match('~video/(.*?)_~', $result->thumbnail_url, $imageId);
                
                return $imageId[1] ?? false;
            } catch (\Exception $e) {
                return false;
            }
        });

        $stubs = ["{id}", "{quality}"];

        $replacements = [$imageId, $this->getConfigAPIPreviewQuality()];

        $url = str_replace($stubs, $replacements, $this->getConfigAPIPreviewUrl());

        return $url;
    }

    public function requestApiData()
    {
        try {
            $url = $this->getConfigAPIURL() . $this->getVideoId();
            $fields = ['fields' => $this->getConfigAPIParts()];
            
            $response = $this->httpClient()->get($url, [
                'query' => $fields,
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->getConfigAPIKey()
                ]
            ]);
            
            if ($response->getStatusCode() !== 200) {
                return false;
            }
            
            return json_decode($response->getBody()->getContents());
        } catch (\Exception $e) {
            return false;
        }
    }

    //todo rewrite to ??(coalesce) operator
    public function getTitle()
    {
        return isset($this->getApiResponse()->name) ? $this->getApiResponse()->name : "";
    }

    public function getDescription()
    {
        return isset($this->getApiResponse()->description) ? $this->getApiResponse()->description : "";
    }

    public function getViewCount()
    {
        return isset($this->getApiResponse()->stats->plays) ? $this->getApiResponse()->stats->plays : 0;
    }

    public function getLikeCount()
    {
        return isset($this->getApiResponse()->metadata->connections->likes->total) ? $this->getApiResponse()->metadata->connections->likes->total : 0;
    }

    public function getDislikeCount()
    {
        return 0;
    }

    public function getFavoriteCount()
    {
        return 0;
    }

    public function getCommentCount()
    {
        return isset($this->getApiResponse()->metadata->connections->comments->total) ? $this->getApiResponse()->metadata->connections->comments->total : 0;
    }

}
