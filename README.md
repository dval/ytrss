# ytrss
ExpressionEngine plugin for embedding recent YouTube videos.

Copy the /ytrss folder to the `/system/user/addons/` folder.

![Install location](https://www.dropbox.com/s/kzf41r0nfp1f86p/install-dir.png?raw=1)


Once that is complete, open the control panel, navigate to Addons, and locate the ytRSS tile.

![Plugin tile](https://www.dropbox.com/s/b8hi9b0dbj4sqm4/tile.png?raw=1)

Click Install.

Tags should now work in EE templates.

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

#### More Information

[View the documentation](ytrss/README.md) for more information on the individual tags.

Cheers
