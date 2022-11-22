<% if $MediaType == 'image' && $MediaImage %>
    <figure class="figure d-block">
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
<% else_if $MediaType == 'video' && $MediaVideoEmbeddedURL %>
    <figure class="video figure ratio ratio-<% if $VideoRatio %>$VideoRatio<% else %>16x9<% end_if %>" data-video-embed-url="$MediaVideoEmbeddedURL" data-element-id="$ID" data-video-type="$MediaVideoProvider">
        <div class="video-overlay" id="playVideo-$ID" style="background-image: url('<% if $MediaVideoCustomThumbnail %>$MediaVideoCustomThumbnail.FocusFill(1440,800).URL<% else %>$MediaVideoEmbeddedThumbnail<% end_if %>')">
            <span class="button">
                <span class="svg-icon">
                    <% include Icons/Includes/PlayRegular %>
                </span>
            </span>
        </div>
        <div class="video-wrapper" id="player-$ID"></div>
    </figure>
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
