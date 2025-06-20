<?php namespace Linecore\ImageStorage;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client;

abstract class AbstractVideoAPI extends Model implements VideoAPIInterface, ConfigurableAPIInterface
{
    use ConfigurableAPITrait;

    protected $videoId;
    protected $curl;
    public $apiResponse;

    public function httpClient()
    {
        if (!$this->curl) {
            $this->curl = new Client([
                'headers' => [
                    'Accept'       => 'application/json',
                    'Content-Type' => 'application/json'
                ],
                'timeout' => 30
            ]);
        }

        return $this->curl;
    }

    public function setVideoId($id)
    {
        $this->videoId = $id;
    }

    public function getVideoId()
    {
        return $this->videoId;
    }

    protected function buildVideoUrl($url, array $urlParams = [])
    {
        $url = $url . $this->getVideoId();

        if (count($urlParams)) {
            $url .= (strpos($url, "?") ? "&" : "?") . http_build_query($urlParams);
        }

        return $url;
    }

    public function getWatchUrl(array $urlParams = [])
    {
        return $this->buildVideoUrl($this->getConfigWatchUrl(), $urlParams);
    }

    public function getEmbedUrl(array $urlParams = [])
    {
        return $this->buildVideoUrl($this->getConfigEmbedUrl(), $urlParams);
    }

    public function getApiResponse()
    {
        if (!$this->getConfigAPIEnabled()) {
            return false;
        }

        if (!$this->apiResponse) {
            $this->apiResponse = $this->getConfigAPICacheMinutes() === false ? $this->requestApiData() : $this->handleCacheApiResponse();
        }

        return $this->apiResponse;
    }

    public function getExistenceErrorMessage()
    {
        $stubs = ["{id}", "{type}"];

        $replacements = [$this->getVideoId(), class_basename($this)];

        $message = str_replace($stubs, $replacements, $this->getConfigApiVideoExistenceError());

        return $message;
    }

    protected function handleCacheApiResponse()
    {
        $tag       = $this->getConfigNamespace() . '.video';
        $cacheName = $this->getConfigPrefix() . "." . $this->getVideoId();
        $minutes   = $this->getConfigAPICacheMinutes();

        $apiResponse = Cache::tags($tag)->remember($cacheName, $minutes, function () {
            return $this->requestApiData();
        });

        return $apiResponse;
    }

}
