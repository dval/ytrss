<?php 
/**
 * This source file is part of the open source project Ytrss 
 * @package Ytrss
 * @author  Dylan Valentine 
 * @link    https://github.com/dval/ytrss
 */
class Ytrss
{
    public $return_data = '';

    private $xmlString = '';
    private $xmlObject = '';

    /**
     *  basic constructor. 
     *  Retrieves a youtube channel xml feed.
     *  SimpleXMLElement transforms feed into object used to 
     *  define {tags}, which are then available to templates.
     */
    public function __construct()
    {
        // make sure only one parameter is preasent
        if ( ee()->TMPL->fetch_param('channel_id') && ee()->TMPL->fetch_param('playlist_id') ){

            // throw error if both parameters exist
            ee()->output->fatal_error('Embeding channel and playlist data in the same tag is not allowed.');

        } elseif (  ee()->TMPL->fetch_param('channel_id') && !ee()->TMPL->fetch_param('playlist_id') ){
            // if channel_id is set...
            // get url from parameter
            $url = "https://www.youtube.com/feeds/videos.xml?channel_id=".ee()->TMPL->fetch_param('channel_id');
            
            // if we get stream at url
            if( $this->xmlString = @file_get_contents($url) ){

                // convert to PHP-ish object
                $this->xmlObject = new SimpleXMLElement($this->xmlString);

                // process innards
                $content = array(
                    // Channel Info
                    'channel_id' => $this->channel_id(),
                    'channel_title' => $this->channel_title(),
                    'channel_published' => $this->channel_published(),
                    'channel_url' => $this->channel_url(),
                    'channel_author' => $this->channel_author(),
                    'raw' => $this->raw(),
                    // Videos
                    'video_list' => $this->map_video_list($this->xmlObject)
                );

                // if there is videos in selected range...
                if ( count( $content['video_list'] ) ){

                    // super convenient 
                    // checks template for any tags, and swaps tag for content
                    $this->return_data = ee()->TMPL->parse_variables(
                        ee()->TMPL->tagdata,
                        array($content)
                    );

                } else {
                    // friendly error message
                    $this->return_data = '<p>Stream is empty.</p>';
                }
            } else {
                // friendly error message
                $this->return_data = '<p>Stream unavailable.</p>';
            }

        } elseif ( !ee()->TMPL->fetch_param('channel_id') && ee()->TMPL->fetch_param('playlist_id') ){
            // if playlist_id is set...
            // get url from parameter
            $url = "https://www.youtube.com/feeds/videos.xml?playlist_id=".ee()->TMPL->fetch_param('playlist_id');

            // get stream at url
            if( $this->xmlString = @file_get_contents($url) ){

                // convert to PHP-ish object
                $this->xmlObject = new SimpleXMLElement($this->xmlString);

                // process innards
                $content = array(
                    // Channel Info
                    'playlist_id' => $this->playlist_id(),
                    'playlist_title' => $this->playlist_title(),
                    'playlist_published' => $this->playlist_published(),
                    'playlist_url' => $this->playlist_url(),
                    'playlist_author' => $this->playlist_author(),
                    'playlist_author_url' => $this->playlist_author_url(),
                    'raw' => $this->raw(),
                    // Videos
                    'video_list' => $this->map_video_list($this->xmlObject)
                );

                // if there is videos in selected range...
                if ( count( $content['video_list'] ) ){

                    // super convenient 
                    // checks template for any tags, and swaps tag for content
                    $this->return_data = ee()->TMPL->parse_variables(
                        ee()->TMPL->tagdata,
                        array($content)
                    );
                    
                } else {
                    // friendly error message
                    $this->return_data = '<p>Stream is empty.</p>';
                }
            } else {
                // friendly error message
                $this->return_data = '<p>Stream unavailable.</p>';
            }
            
        }
    }

    //
    //  Channel Information
    //
    private function channel_id()
    {
        return $this->xmlObject->children('yt')->channelId;
    }

    private function channel_title()
    {
        return $this->xmlObject->title;
    }

    private function channel_published()
    {
        return $this->xmlObject->published;
    }

    private function channel_url()
    {
        return $this->xmlObject->link[1]['href'];
    }

    private function channel_author()
    {
        return $this->xmlObject->author->name;
    }

    private function raw()
    {
        return $this->xmlString;
    }


    //
    //  Playlist Information
    //
    private function playlist_id()
    {
        return $this->xmlObject->children('yt')->channelId;
    }

    private function playlist_title()
    {
        return $this->xmlObject->title;
    }

    private function playlist_published()
    {
        return $this->xmlObject->published;
    }

    private function playlist_url()
    {
        return $this->xmlObject->link['href'];
    }

    private function playlist_author()
    {
        return $this->xmlObject->author->name;
    }

    private function playlist_author_url()
    {
        return $this->xmlObject->author->uri;
    }


    //
    //  Video List
    //
    private function map_video_list($xml)
    {
        $videos = array();
        $limit = (int) ee()->TMPL->fetch_param('limit', 10);

        //limit offset
        $offlimit = 14;
        $prefered = (int) ee()->TMPL->fetch_param('offset', 0);
        $offset = ($prefered > $offlimit ) ? $offlimit : $prefered;

        // I just wanted an integer count
        $i = 0;

        foreach ($xml->entry as $index => $item) {
            
            $videos[] = array(
                'video_index' => $i++,
                'video_id' => $this->video_id($item),
                'video_title' => $this->video_title($item),
                'video_link' => $this->video_link($item),
                'video_description' => $this->video_description($item),
                'video_width' => $this->video_width($item),
                'video_height' => $this->video_height($item),
                'video_thumbnail' => $this->video_thumbnail($item),
                'video_thumbnail_width' => $this->video_thumbnail_width($item),
                'video_thumbnail_height' => $this->video_thumbnail_height($item),
                'video_rating' => $this->video_rating($item),
                'video_vote_count' => $this->video_vote_count($item),
                'video_vote_min' => $this->video_vote_min($item),
                'video_vote_max' => $this->video_vote_max($item),
                'video_views' => $this->video_views($item),
                'video_embed' => $this->video_embed($item)
            );
        }
        return array_slice($videos, $offset, $limit);
    }

    private function video_id($item)
    {
        return $item->children('yt', true)->videoId;
    }

    private function video_title($item)
    {
        return $item->title;
    }

    private function video_link($item)
    {
        return $item->link['href'];
    }

    private function video_description($item)
    {
        return $item->children('media', true)->group->description;
    }

    private function video_width($item)
    {
        return  $item->children('media', true)->group->content->attributes()['width'];
    }

    private function video_height($item)
    {
        return $item->children('media', true)->group->content->attributes()['height'];
    }

    private function video_thumbnail($item)
    {
        return $item->children('media', true)->group->thumbnail->attributes()['url'];
    }

    private function video_thumbnail_width($item)
    {
        return $item->children('media', true)->group->thumbnail->attributes()['width'];
    }

    private function video_thumbnail_height($item)
    {
        return $item->children('media', true)->group->thumbnail->attributes()['height'];
    }

    private function video_rating($item)
    {
        return $item->children('media', true)->group->community->starRating->attributes()['average'];
    }

    private function video_vote_count($item)
    {
        return $item->children('media', true)->group->community->starRating->attributes()['count'];
    }

    private function video_vote_min($item)
    {
        return $item->children('media', true)->group->community->starRating->attributes()['min'];
    }

    private function video_vote_max($item)
    {
        return $item->children('media', true)->group->community->starRating->attributes()['max'];
    }

    private function video_views($item)
    {
        return $item->children('media', true)->group->community->statistics->attributes()['views'];
    }
    
    private function video_embed($item)
    {
        // video embed options
        $id = $item->children('yt', true)->videoId;
        $vt = $item->title;
        $vw = (string) ee()->TMPL->fetch_param('embed_width', '100%');
        $vh = (string) ee()->TMPL->fetch_param('embed_height', 'auto');
        $mh = (string) ee()->TMPL->fetch_param('embed_min_height', '200px');
        $pe = (bool) ee()->TMPL->fetch_param('privacy_enhanced', false) ? '-nocookie':'';
        $sc = (bool) ee()->TMPL->fetch_param('controls', true) ? '?controls=0':'';

        $embed_string = '<iframe style="width:' . $vw . '; height:' . $vh . '; min-height:' . $mh . ';" src="https://www.youtube' . $pe . '.com/embed/' . $id . $sc .'" title="' . $vt .
            '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';

        return $embed_string;
    }

}