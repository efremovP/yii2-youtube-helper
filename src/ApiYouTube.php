<?php

namespace efremovP\youtube;


/**
 *
 * Полачем список видео роликов с YouTube, для добавления на сайт
 *
 * @author Ефремов Петр
 * @since 2.0
 */
class ApiYouTube
{
    private $api_key = '';
    private $video_list = [];

    public function __construct($youtube_api_key)
    {
        $this->api_key = $youtube_api_key;
    }

    /**
     * id канала на youtube
     * @param string $url
     * @return string
     */
    public function getList($id_channel)
    {
        $next_page_token = $this->getPartVideoList($id_channel);

        for ($i = 1; $i <= 10; $i++) {
            $next_page_token = $this->getPartVideoList($id_channel, $next_page_token);
        }

        return $this->video_list;
    }

    public function getItem($id_video)
    {
        $get_data = file_get_contents('https://www.googleapis.com/youtube/v3/videos?id=' . $id_video . '&part=snippet&key=' . $this->api_key);
        $get_data = json_decode($get_data, true);

        if (!isset($get_data["items"][0]['snippet'])) {
            return '';
        }

        return $get_data["items"][0]['snippet'];
    }

    public function getImage($id_video)
    {
        $video_item = $this->getItem($id_video);

        return $video_item['thumbnails']['standard']['url'];
    }

    public function getPublishDate($id_video)
    {
        $video_item = $this->getItem($id_video);

        if (!isset($video_item['publishedAt'])) {
            return '';
        }

        $date_time = new \DateTime($video_item['publishedAt']);
        $date = $date_time->format('Y-m-d H:i:s');

        return $date;
    }

    private function getPartVideoList($id_channel, $next_page_token = '')
    {
        $limit = 50;

        $next_page_token = $next_page_token != '' ? '&pageToken=' . $next_page_token : '';

        $get_data = file_get_contents('https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&playlistId=' . $id_channel . $next_page_token . '&maxResults=' . $limit . '&key=' . $this->api_key);
        $get_data = json_decode($get_data, true);

        $next_page_token = isset($get_data["nextPageToken"]) ? $get_data["nextPageToken"] : '';

        $video_list = $get_data["items"];

        foreach ($video_list as $i => $video_item) {
            $this->video_list[$video_item['snippet']['resourceId']["videoId"]] = [
                'key' => $video_item['snippet']['resourceId']["videoId"],
                'title' => isset($video_item['snippet']['title']) ? $video_item['snippet']['title'] : '',
                'img' => isset($video_item['snippet']['thumbnails']['standard']['url']) ? $video_item['snippet']['thumbnails']['standard']['url'] : '',
                'date' => $this->getPublishDate($video_item['snippet']['resourceId']["videoId"])
            ];
        }


        return $next_page_token;
    }
}