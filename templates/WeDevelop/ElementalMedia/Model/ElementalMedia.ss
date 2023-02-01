<% if $ShowTitle && $Title %>
    <$TitleTag class="$TitleSizeClass">
        $Title.RAW
    </$TitleTag>
<% end_if %>
<% if $MediaType == 'image' && $MediaImage %>
    <% if $MediaCaption %><div class="captionImage"><% end_if %>
    <figure class="image<% if $BulmaRatio %> $BulmaRatio<% end_if %>">
        <% if $MediaRatio == '1x1' %>
            <img src="$MediaImage.FocusFill(1440,1440).URL" loading="lazy" alt="$MediaImage.Title" class="img-fluid" width="1440" height="1440">
        <% else_if $MediaRatio == '4x3' %>
            <img src="$MediaImage.FocusFill(1440,1080).URL" loading="lazy" alt="$MediaImage.Title" class="img-fluid" width="1440" height="1080">
        <% else_if $MediaRatio == '16x9' %>
            <img src="$MediaImage.FocusFill(1440,810).URL" loading="lazy" alt="$MediaImage.Title" class="img-fluid" width="1440" height="810">
        <% else %>
            <img src="$MediaImage.ScaleMaxWidth(1440).URL" loading="lazy" alt="$MediaImage.Title" class="img-fluid" width="$MediaImage.ScaleMaxWidth(1440).Width" height="$MediaImage.ScaleMaxWidth(1440).Height">
        <% end_if %>
    </figure>
    <% if $MediaCaption %><p class="caption leftAlone">$MediaCaption</p><% end_if %>
    <% if $MediaCaption %></div><% end_if %>
<% else_if $MediaType == 'video' && $MediaVideoFullURL && $MediaVideoEmbeddedURL %>
    <% if $MediaCaption %><div class="captionImage"><% end_if %>
    <figure class="video image<% if $BulmaRatio %> $BulmaRatio<% end_if %>" data-video-embed-url="$MediaVideoEmbeddedURL" data-element-id="$ID" data-video-type="$MediaVideoProvider">
        <div class="video-thumbnail<% if $MediaVideoHasOverlay %> has-overlay<% end_if %>" id="playVideo-$ID" style="background-image: url('<% if $MediaVideoCustomThumbnail %>$MediaVideoCustomThumbnail.FocusFill(1440,800).URL<% else %>$MediaVideoEmbeddedThumbnail<% end_if %>')">
            <span class="button is-dark is-square">
                <span class="svg-icon">
                    <% include Icons/Includes/PlayRegular %>
                </span>
            </span>
        </div>
        <div class="video-wrapper" id="player-$ID"></div>
    </figure>
    <% if $MediaCaption %><p class="caption leftAlone">$MediaCaption</p><% end_if %>
    <% if $MediaCaption %></div><% end_if %>
    <script type="application/ld+json">
        {
            "@context": "http://schema.org",
            "@type": "VideoObject",
            "playerType": "HTML5",
            "embedUrl": "$MediaVideoEmbeddedURL",
            "name": "$MediaVideoEmbeddedName",
            "description": "$MediaVideoEmbeddedDescription",
            "thumbnailUrl": "$MediaVideoEmbeddedThumbnail",
            "uploadDate": "$MediaVideoEmbeddedCreated"
        }
    </script>
<% end_if %>
