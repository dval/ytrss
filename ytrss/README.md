# ytRSS

An ExpressionEngine plugin that retrieves and parses YouTube XML for a channel or playlist.

## Requirements

Probably requires agreeing to using YouTube and the YouTube terms of service:

https://developers.google.com/youtube/terms/api-services-terms-of-service

## Limitations

This relys on YouTube's XML feed of recent videos which only provides 15 recent videos for a channel or playlist. As you add more videos to your channel, older videos will no longer be available in the XML feed.

A playlist feed can be static, possibly better for articles.

The channel feed may be better suited for a 'Recent Work' or landing page of some kind.

## Usage

### `{exp:ytrss} `


This is the main tag in this plugin. Using more than one instance allows multiple channels in the same template.


#### Example Usage

Use either the `channel_id` parameter or `playlist_id`, but not both.

__Create embed tag similar to 'share' link__

    {exp:ytrss channel_id='YOUR-CHANNEL-ID' limit='1' offset='2' embed_width='100%'}
        <h3><a href="{channel_url}">{channel_title}</a></h3>
        <p>On the tube since:{channel_published}</p>
        {video_list}
            <h4><a href="{video_link}">{video_title}</a></h4>
            <p>{video_description}</p>
            {video_embed}
        {/video_list}
    {/exp:ytrss}

__Create low-bandwidth links to YouTube pages__

    {exp:ytrss playlist_id='YOUR-PLAYLIST-ID' limit='3' offset='1' embed_width='100%'}
        <h3>Watch my recent viedo!</h3>
        {video_list}
		<a href="{video_link}"><h4>{video_title}</h4>
            <img src="{video_thumbnail}"></a>
            <p>{video_description}</p>
        {/video_list}
    {/exp:ytrss}

#### Available Parameters

- channel_id - ID of the channel you want to subscribe to.
- playlist_id - ID of the playlist you want to subscribe to.
- limit - Number of videos to show. Default is 10. YouTube max is 15 videos.
- offset - Skip a number of items in the display of the feed. Default is 0 but limited to 14, because video list will only ever be 15 videos.
- embed_width - Width of the embed iframe. Default is '100%'. Any valid CSS should work.
- embed_height - Height of the embed iframe. Default is 'auto'. Any valid CSS should work.
- embed_min_height - Minimum height of the embed iframe. Default is '200px'. Any valid CSS should work.


#### Single Variables

##### Using `channel_id`
- {channel_id}          - channel ID (sometimes reffered to as channel number)
- {channel_title}       - title or author's user name
- {channel_url}         - link to the channel landing page
- {channel_published}   - date channel was started
- {channel_author}      - may be identical to channel name


##### Using `playlist_id`
- {playlist_id}         - playlist ID or number
- {playlist_title}      - title
- {playlist_url}        - link to playlist on YouTube
- {playlist_published}  - date playlist was created
- {playlist_author}     - user that created playlist
- {playlist_author_url} - link to author's main channel


#### Pair Variables

##### video_list

The `{video_list}` pair variable contains all of the data for each video. It contains 16 variables:

    {video_list}
        {video_index}               - a number to keep track of order
        {video_id}                  - video ID
        {video_title}               - video title
        {video_link}                - link to video page on YouTube
        {video_description}         - description text
        {video_width}               - YouTube's dimensions for media type
        {video_height}              -
        {video_thumbnail}           - URL for thumbnail representing video
        {video_thumbnail_width}     - YouTube's dimensions for media type
        {video_thumbnail_height}    - 
        {video_rating}              - video's average rating
        {video_vote_count}          - how many votes have been cast
        {video_vote_min}            - lowest possible vote ( always 1.0 )
        {video_vote_max}            - highest possible vote ( always 5.0 )
        {video_views}               - total views
        {video_embed}               - convenience tag that otputs the same html as the *embed* option, in a video's share menu
    {/video_list}


## Change Log

### 1.0

- From nothing to something, but only 15 videos
